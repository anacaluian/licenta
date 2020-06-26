(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["POST"],"uri":"api\/auth\/register","name":"register","action":"App\Http\Controllers\AuthController@register"},{"host":null,"methods":["POST"],"uri":"api\/auth\/login","name":"login","action":"App\Http\Controllers\AuthController@login"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/refresh","name":"refresh","action":"App\Http\Controllers\AuthController@refresh"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/user","name":"user","action":"App\Http\Controllers\AuthController@user"},{"host":null,"methods":["POST"],"uri":"api\/auth\/reset","name":"forgot-password","action":"App\Http\Controllers\AuthController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/users\/{id}","name":null,"action":"App\Http\Controllers\UserController@show"},{"host":null,"methods":["POST"],"uri":"api\/auth\/logout","name":"logout","action":"App\Http\Controllers\AuthController@logout"},{"host":null,"methods":["POST"],"uri":"api\/auth\/profile\/photo","name":"profile.photo","action":"App\Http\Controllers\UserController@profilePhoto"},{"host":null,"methods":["POST"],"uri":"api\/auth\/user\/update","name":"user.update","action":"App\Http\Controllers\UserController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/members","name":"members","action":"App\Http\Controllers\UserController@members"},{"host":null,"methods":["POST"],"uri":"api\/auth\/members\/edit","name":"members.edit","action":"App\Http\Controllers\UserController@membersEdit"},{"host":null,"methods":["POST"],"uri":"api\/auth\/members\/delete","name":"members.delete","action":"App\Http\Controllers\UserController@membersDelete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/clients","name":"clients","action":"App\Http\Controllers\UserController@clients"},{"host":null,"methods":["POST"],"uri":"api\/auth\/clients\/edit","name":"clients.edit","action":"App\Http\Controllers\UserController@clientsEdit"},{"host":null,"methods":["POST"],"uri":"api\/auth\/clients\/delete","name":"clients.delete","action":"App\Http\Controllers\UserController@clientsDelete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/projects","name":"projects","action":"App\Http\Controllers\ProjectController@index"},{"host":null,"methods":["POST"],"uri":"api\/auth\/project\/data","name":"projects.data","action":"App\Http\Controllers\ProjectController@project"},{"host":null,"methods":["POST"],"uri":"api\/auth\/projects\/create","name":"projects.create","action":"App\Http\Controllers\ProjectController@create"},{"host":null,"methods":["POST"],"uri":"api\/auth\/projects\/edit","name":"projects.edit","action":"App\Http\Controllers\ProjectController@edit"},{"host":null,"methods":["POST"],"uri":"api\/auth\/projects\/state","name":"projects.state","action":"App\Http\Controllers\ProjectController@state"},{"host":null,"methods":["POST"],"uri":"api\/auth\/projects\/delete","name":"projects.delete","action":"App\Http\Controllers\ProjectController@delete"},{"host":null,"methods":["POST"],"uri":"api\/auth\/projects\/members","name":"projects.members","action":"App\Http\Controllers\ProjectController@members"},{"host":null,"methods":["POST"],"uri":"api\/auth\/tasks","name":"tasks","action":"App\Http\Controllers\TaskController@index"},{"host":null,"methods":["POST"],"uri":"api\/auth\/tasks\/create","name":"tasks.create","action":"App\Http\Controllers\TaskController@create"},{"host":null,"methods":["POST"],"uri":"api\/auth\/tasks\/update","name":"tasks.update","action":"App\Http\Controllers\TaskController@update"},{"host":null,"methods":["POST"],"uri":"api\/auth\/tasks\/update\/task","name":"tasks.update.task","action":"App\Http\Controllers\TaskController@updateTask"},{"host":null,"methods":["POST"],"uri":"api\/auth\/tasks\/delete","name":"tasks.delete","action":"App\Http\Controllers\TaskController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/comments\/{project}\/{task}","name":"comments","action":"App\Http\Controllers\CommentController@index"},{"host":null,"methods":["POST"],"uri":"api\/auth\/comments\/create","name":"comments.create","action":"App\Http\Controllers\CommentController@create"},{"host":null,"methods":["POST"],"uri":"api\/auth\/comments\/upload\/{task}","name":"comments.upload","action":"App\Http\Controllers\CommentController@upload"},{"host":null,"methods":["POST"],"uri":"api\/auth\/comments\/delete","name":"comments.delete","action":"App\Http\Controllers\CommentController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/notes\/{project}","name":"notes","action":"App\Http\Controllers\NoteController@index"},{"host":null,"methods":["POST"],"uri":"api\/auth\/notes\/create","name":"notes.create","action":"App\Http\Controllers\NoteController@create"},{"host":null,"methods":["POST"],"uri":"api\/auth\/notes\/update","name":"notes.update","action":"App\Http\Controllers\NoteController@update"},{"host":null,"methods":["POST"],"uri":"api\/auth\/notes\/delete","name":"notes.delete","action":"App\Http\Controllers\NoteController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/files\/{project}","name":"files","action":"App\Http\Controllers\FileController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/files\/download\/{file}","name":"files.download","action":"App\Http\Controllers\FileController@download"},{"host":null,"methods":["POST"],"uri":"api\/auth\/files\/create","name":"files.create","action":"App\Http\Controllers\FileController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/times\/{project}\/{member}","name":"times","action":"App\Http\Controllers\TimeController@index"},{"host":null,"methods":["POST"],"uri":"api\/auth\/times\/create","name":"times.create","action":"App\Http\Controllers\TimeController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/times\/month","name":"times.month","action":"App\Http\Controllers\TimeController@monthlyTime"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/times\/delete","name":"times.delete","action":"App\Http\Controllers\TimeController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/emails","name":"emails","action":"App\Http\Controllers\GmailController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/emails\/sync","name":"emails.sync","action":"App\Http\Controllers\GmailController@sync"},{"host":null,"methods":["POST"],"uri":"api\/auth\/emails\/task","name":"emails.task","action":"App\Http\Controllers\GmailController@task"},{"host":null,"methods":["POST"],"uri":"api\/auth\/emails\/remove","name":"emails.remove","action":"App\Http\Controllers\GmailController@remove"},{"host":null,"methods":["POST"],"uri":"api\/auth\/emails\/delete","name":"emails.delete","action":"App\Http\Controllers\GmailController@delete"},{"host":null,"methods":["POST"],"uri":"api\/auth\/emails\/request","name":"emails.request","action":"App\Http\Controllers\GmailController@requestAuthLink"},{"host":null,"methods":["POST"],"uri":"api\/auth\/emails\/token","name":"emails.token","action":"App\Http\Controllers\GmailController@generateToken"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth\/activities\/{project_id}","name":"activities","action":"App\Http\Controllers\ActivityController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"{any?}","name":null,"action":"Closure"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

