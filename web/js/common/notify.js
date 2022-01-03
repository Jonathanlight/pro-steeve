function notify(title, body, status){

    status = typeof status !== 'undefined' ? status : "success";

    $.notify(body, status);
}