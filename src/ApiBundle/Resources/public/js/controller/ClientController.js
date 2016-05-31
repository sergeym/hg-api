class ClientController {

    constructor() {
        this.$clients = $('#clients');
        this.$editClientModal = $('#edit-client-modal');
        this.$editClientForm = $('#edit-client-modal form');

        this.$editClientForm.submit(this.onEditClientFormSubmit.bind(this))
        this.$editClientModal.on('show.bs.modal', this.onModalShow.bind(this))
    }

    responseToData(response) {
        return [
            response.id,
            response.name,
            response.allowedGrantTypes,
            response.createdAt,
            response.allowedOrigins,
            response.redirectUris,
            response.randomId
        ];
    }

    setClientsData(clientsData) {

        let data = [];
        for (let i in clientsData) {
            data.push(this.responseToData(clientsData[i]));
        }

        $('#clients').DataTable({
            data: data,
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return '<a href="#edit-client-modal" data-toggle="modal" data-id="' + row[0] + '">' + data + '</a>'
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        var colors = {
                            authorization_code: 'label-primary',
                            password: 'label-danger',
                            refresh_token: 'label-warning',
                            token: 'label-success',
                            client_credentials: 'label-info'
                        };
                        var result = data.map(function (grant) {
                            var text = grant.split('_').map(function (val) {
                                return val.substr(0, 1).toUpperCase()
                            });
                            return '<span class="grant_type label ' + colors[grant] + '" title="' + grant.replace('_', ' ') + '">' + text.join('') + '</span>';
                        });
                        return result.join(' ');
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        var date = new Date(data);
                        var formatter = new Intl.DateTimeFormat("ru", {
                            weekday: "long",
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "numeric",
                            minute: "numeric"
                        });

                        return formatter.format(date);
                    },
                    "targets": 3
                },
                {
                    "visible": false,
                    "searchable": false,
                    "targets": [4, 5, 6]
                }
            ]
        });
    };

    updateData(response) {
        let tr = this.$clients.find('a[data-id="'+response.id+'"]').parents('tr');
        let data = this.responseToData(response);
        this.$clients.DataTable().row(tr).data(data).draw();
    }

    onModalShow(event) {
        var tr = $(event.relatedTarget).parents('tr');
        var data = this.$clients.DataTable().row(tr).data();
        this.$editClientForm.data('id', data[0]);

        this.fillForm({
            name: data[1],
            redirectUris: data[5].join("\n"),
            allowedOrigins: data[4].join("\n"),
            allowedGrantTypes: data[2],
        });
    };

    fillForm(formData) {
        this.$editClientForm.find('input[name="name"]').val(formData.name);
        this.$editClientForm.find('textarea[name="redirectUris"]').val(formData.redirectUris);
        this.$editClientForm.find('textarea[name="allowedOrigins"]').val(formData.allowedOrigins);
        this.$editClientForm.find('input[name="allowedGrantTypes[]"]').prop('checked', false).each(function (index, el) {
            $(el).prop('checked', (formData.allowedGrantTypes.indexOf($(el).val()) > -1))
        });
    };

    serializeForm() {
        var allowedGrantTypes = this.$editClientForm.find('input[name="allowedGrantTypes[]"]').filter(function (index, el) {
            return $(el).is(':checked');
        }).map(function (index, el) {
            return $(el).val();
        }).get();

        return {
            name:  this.$editClientForm.find('input[name="name"]').val(),
            redirectUris: $(this.$editClientForm.find('textarea[name="redirectUris"]').val().split("\n")).filter(function (index, el) {
                return el.trim().length > 0
            }).get(),
            allowedOrigins: $(this.$editClientForm.find('textarea[name="allowedOrigins"]').val().split("\n")).filter(function (index, el) {
                return el.trim().length > 0
            }).get(),
            allowedGrantTypes: allowedGrantTypes
        };
    }

    lockForm() {
        $('button[form="edit-client-form"]').button('loading');
    }

    unlockForm() {
        $('button[form="edit-client-form"]').button('reset');
    }

    onEditClientFormSubmit(event) {
        event.preventDefault();
        let data = this.serializeForm();
        let id = this.$editClientForm.data('id');
        this.lockForm();
        if (id) {
            HG.put('clients/'+id, data, this.onUpdate.bind(this));
        } else {
            HG.post('clients')
        }
    }

    onUpdate(promise) {
        const self = this;
        this.unlockForm();
        if (promise.ok) {
            promise.json().then(function (data) {
                self.updateData(data)
                self.$editClientModal.modal('hide');
            })
        } else if (promise.status == 400) { // bad request
            //form validation fail
            promise.json().then(function (response) {
                self.displayErrors(response.errors);
            })
        } else {

        }
    }

    displayErrors(errors) {
        for(let name in errors) {
            $('input[name="'+name+'"], textarea[name="'+name+'"]')
                .attr('title', errors[name])
                .tooltip()
                .on('change', function (event) {
                    $(this)
                        .attr('title', "")
                        .tooltip('destroy')
                        .parents('.form-group')
                        .removeClass('has-error');
                })
                .parents('.form-group')
                .addClass('has-error')


        }

    }
}
