$(document).ready(function() {
        $.ajax({
                url: "http://www.usertech.cn:3000/api/elecitems/0000000035998155"
        }).then(function(data) {
                $('.greeting-id').append(data.id);
                $('.greeting-content').append(data.content);
        });
});
