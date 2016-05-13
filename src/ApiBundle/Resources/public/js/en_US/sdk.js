try {
    window.HG || (function (window, fb_fif_window) {

        function bindContext(fn, thisArg) {
            return function _sdkBound() {
                return apply.call(fn, thisArg, arguments);
            };
        }

        var global = {__type: 'JS_SDK_SANDBOX', window: window, document: window.document};
        var sandboxWhitelist = ['setTimeout', 'setInterval', 'clearTimeout', 'clearInterval'];
        for (var i = 0; i < sandboxWhitelist.length; i++) {
            global[sandboxWhitelist[i]] = bindContext(window[sandboxWhitelist[i]], window);
        }

        (function () {
            var self = window;
            var config = {};
            var loginStatusListeners = [];
            var loginWindow;
            var messageEmitterInterval;
            var auth = null;
            var defaultScope = 'profile';
            var appHost = 'api.hanggliding.ru'
            var appSchema = document.location.protocol;
            var appOrigin = appSchema + '//' + appHost;
            var appAuthReturnUrl = appSchema + '//' + appHost + '/oauth/v2/auth_receiver';
            var state = null;
            var stateLength = 32;

            var fetchLoginStatus = function (appId, scope) {
                return fetch('//'+appHost+'/oauth/v2/auth_login_status?client_id='+appId, {
                    credentials: 'include'
                }).then(function(response) {
                        var data = response.json();
                        if (response.status >= 200 && response.status < 300) {
                            return Promise.resolve(data)
                        } else {
                            return Promise.reject(new Error(response.statusText))
                        }
                    }
                ).catch(function(err) {
                        console.error('Fetch Error :-S', err);
                });
            };

            var updateAuth = function (response) {
                auth = response.authResponse || null;
                for (var i = 0; i < loginStatusListeners.length; i++) {
                    loginStatusListeners[i](response)
                }
            };

            var isSufficientScope = function (given, required) {
                if (!given && !required) return true;

                if (given && required) {
                    var listGiven = given ? given.split(' ') : [];
                    var listRequired = required ? required.split(' ') : [];
                    for(var i in listRequired) {
                        if (listGiven.indexOf(listRequired[i]) == -1) {
                            return false;
                        }
                    }
                    return true;
                }
                return false
            };

            var enableLoginListeners = function (e) {
                messageEmitterInterval = setInterval(function () {
                    loginWindow.postMessage(state, appAuthReturnUrl);
                }, 500);

                window.addEventListener('message', function (ev) {
                    if (ev.origin == appOrigin) {
                        if (ev.data && ev.data.authResponse && state == ev.data.authResponse.state) {
                            window.removeEventListener('message', this);
                            clearInterval(messageEmitterInterval);
                            updateAuth(ev.data)
                        }
                    }
                });

            };

            var apiLogout = function () {
                return fetch('//'+appHost+'/oauth/v2/auth_logout', {
                    headers: {
                        'Authorization': "Bearer " + auth.access_token,
                    }
                }).then(function(response) {
                        if (response.status == 204) {
                            return Promise.resolve(response)
                        } else {
                            return Promise.reject(new Error(response.statusText))
                        }
                    }
                ).catch(function(err) {
                    console.log('Fetch Error :-S', err);
                });
            };

            var logoutHandler = function () {
                apiLogout().then(function () {
                    updateAuth({status:'not_authorized'})
                })
            };

            var authorizeHandler = function (e) {
                e.preventDefault();
                var t = e.target;
                var scope = t.getAttribute('data-scope');
                var query = [
                    'client_id='+config.appId,
                    'display=touch',
                    'redirect_uri='+encodeURIComponent(appAuthReturnUrl),
                    'response_type=token',
                    'scope='+encodeURIComponent(scope ? scope : defaultScope),
                    'state='+state,
                ];
                var w=450;
                var h=450;
                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                var left = ((width / 2) - (w / 2)) + dualScreenLeft;
                var top = ((height / 2) - (h / 2)) + dualScreenTop;

                var params = 'menubar=no,location=no,resizable=no,scrollbars=no,status=no,width=' + w + ', height=' + h + ', top=' + top + ', left=' + left;
                loginWindow = window.open("//"+appHost+"/oauth/v2/auth?" + query.join('&'), "Sign In", params)
                enableLoginListeners()
            };

            var updateWidget = function (w, status, authResponse) {
                var scope = w.getAttribute('data-scope');

                w.setAttribute('data-status', status);
                w.removeEventListener("click", logoutHandler);
                w.removeEventListener("click", authorizeHandler);

                switch (status) {
                    case 'connected':
                        w.setAttribute('data-hg-authorized-full', isSufficientScope(authResponse.scope, scope))
                        w.addEventListener("click", logoutHandler)
                        if (w.hasAttribute('data-label-connected')) {
                            w.textContent = w.getAttribute('data-label-connected');
                        }
                        break;
                    case 'not_authorized':
                        w.addEventListener( "click", authorizeHandler)
                        if (w.hasAttribute('data-label-not_authorized')) {
                            w.textContent = w.getAttribute('data-label-not_authorized');
                        }
                        break;
                    case 'unknown':
                        w.addEventListener( "click", authorizeHandler)
                        if (w.hasAttribute('data-label-unknown')) {
                            w.textContent = w.getAttribute('data-label-unknown');
                        }
                }
            };

            var xhgmlInit = function (_config) {

                var btns = document.querySelectorAll('[data-hg-login-button]');

                for (var i = 0; i < btns.length; i++)       {
                    var b = btns[i];

                    (function(b) {
                        getLoginStatus(function (response) {
                            updateWidget(b, response.status, response.authResponse)
                        })
                    })(b);
                }
            };

            var init = function (_config) {
                config = _config
                xhgmlInit(_config)

                state = Math.round((Math.pow(36, stateLength + 1) - Math.random() * Math.pow(36, stateLength))).toString(36).slice(1);

                fetchLoginStatus(_config.appId).then(function (response, error) {
                    if (response) {
                        updateAuth(response)
                    }
                });
            };
            
            var getLoginStatus = function (_callback) {
                if (_callback instanceof Function && loginStatusListeners.indexOf(_callback) == -1) {
                    loginStatusListeners.push(_callback)
                }
            }

            var apiGet = function (path, callback) {
                return fetch('//'+appHost+'/api/'+path, {
                    headers: {
                        'Authorization': "Bearer " + auth.access_token,
                    }
                }).then(function(response) {
                        var data = response.json();
                        if (response.status >= 200 && response.status < 300) {
                            return Promise.resolve(data).then(callback);
                        } else {
                            return Promise.reject(new Error(response.statusText))
                        }
                    }
                ).catch(function(err) {
                    console.error('Fetch Error :-S', err);
                });
            }

            window.HG = {
                init: init,
                getLoginStatus: getLoginStatus,
                get:apiGet
            };

            if (window.hgAsyncInit && !window.hgAsyncInit.hasRun) {
                window.hgAsyncInit.hasRun = true;
                window.hgAsyncInit();
            }
        }).call(global);

    })(window.inDapIF ? parent.window : window, window);
} catch (e) {
    new Image().src = "https:\/\/www.hanggliding.ru\/" + 'common/scribe_endpoint.php?c=jssdk_error&m=' + encodeURIComponent('{"error":"LOAD", "extra": {"name":"' + e.name + '","line":"' + (e.lineNumber || e.line) + '","script":"' + (e.fileName || e.sourceURL || e.script) + '","stack":"' + (e.stackTrace || e.stack) + '","namespace":"HG","message":"' + e.message + '"}}');
}