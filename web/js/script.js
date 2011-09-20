var Foobar = Foobar || {};
Foobar.dnsresolv = function (baseUrl) {
    var $table = $('#dns-table'),
        servers = [],
        requests = [];
    
    function clearRequests() {
        _.each(requests, function (xhr) {
            xhr.abort();
        });
        requests = [];
        $('.result').html('');
    }

    $.get(baseUrl+'/servers', function (response) {
        servers = response.servers;
    });

    $('#dns-query').submit(function (e) {
        var form = $(this),
            name = form.find('*[name="name"]').val(),
            type = form.find('*[name="type"]').val();

        clearRequests();

        _.each(servers, function (server) {
            var xhr,
                request = {
                    server: server.hostname,
                    name: name,
                    type: type
                };

            xhr = $.get(baseUrl+'/resolve', request, function (result) {
                var $server = $('*[data-server="'+server.hostname+'"]');
                $server.find('.result').html(result.answer && result.answer[0].data);
            });

            requests.push(xhr);
        });

        e.preventDefault();
    });
};
