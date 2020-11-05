(function() {
	function ready(fn) {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

	ready(function() {
        window.Pico = {
            break_selector: pp_vars.break_selector || null,
            debug_info: pp_vars,
            post: {
                content_chop: !!pp_vars.show_read_more_button,
                post_id: pp_vars.post_id || null,
                post_title: pp_vars.post_title || null,
                post_type: pp_vars.post_type || null,
                resource_ref: pp_vars.resource_ref || null,
                taxonomies: pp_vars.taxonomies || null,
                url: window.location.href
            },
            publisher_id: pp_vars.publisher_id,
            plugin_version: pp_vars.plugin_version,
        }
        if (!!pp_vars.categories) {
            window.Pico.post.categories = JSON.parse(pp_vars.categories) || '';
        }
        if (!!pp_vars.tags) {
            window.Pico.post.tags = JSON.parse(pp_vars.tags) || '';
        }

        var element = document.getElementsByTagName("head")[0];

        var script = document.createElement("script");
        script.setAttribute("type", "text/javascript");
        script.setAttribute("async", !0);
        script.setAttribute("defer", !0);
        script.setAttribute("crossorigin", "anonymous");

        if(pp_vars.pico_context === 'widget') {
            script.setAttribute("src", pp_vars.widget_endpoint + "/static/js/bundle.js?client_id=" + pp_vars.publisher_id + "&widget_version=" + pp_vars.widget_version);
        } else {
            script.setAttribute("data-pico-id", pp_vars.publisher_id);
            script.setAttribute("src", pp_vars.widget_endpoint + "/load/build.js");
        }
        element.appendChild(script);

	});
})();
