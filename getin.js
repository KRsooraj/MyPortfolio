$('#registerForm').on('submit', function(event) {
    event.preventDefault();
    var formData = $("#registerForm").serialize();
    blockMessage('', '1000', 'Please wait.');
    $.ajax({
        url: window.location.origin + "/actions/register.php",
        type: "POST",
        data: formData,
        dataType: 'JSON',
        success: function(data) {
            var result = JSON.parse(JSON.stringify(data));
            if (result.status == 1) {
                blockMessage('success', '1000', result.message);
                window.location.href = window.location.origin + '/verify.php?token=' + result.token;
            } else {
                blockMessage('danger', '1000', result.message);
            }
        }
    });
});
$('#verfyForm').on('submit', function(event) {
    event.preventDefault();
    var formData = $("#verfyForm").serialize();
    blockMessage('', '1000', 'Please wait.');
    $.ajax({
        url: window.location.origin + "/actions/verify.php",
        type: "POST",
        data: formData,
        dataType: 'JSON',
        success: function(data) {
            var result = JSON.parse(JSON.stringify(data));
            if (result.status == 1) {
                blockMessage('success', '1000', result.message);
                window.location.href = window.location.origin + '/index.php';
            } else {
                blockMessage('danger', '1000', result.message);
            }
        }
    });
});
$('#getinForm').on('submit', function(event) {
    event.preventDefault();
    blockMessage('', '0', 'Authenticating');
    var formData = $("#getinForm").serialize();
    $.ajax({
        url: window.location.origin + "/actions/getin.php",
        type: "POST",
        data: formData,
        dataType: 'JSON',
        success: function(data) {
            var result = JSON.parse(JSON.stringify(data));
            if (result.status == 1) {
                blockMessage('success', '1000', result.message);
                location.reload();
            } else if (result.status == 2) {
                blockMessage('success', '1000', result.message);
                window.location.href = window.location.origin + '/verify.php?token=' + result.token;
            } else {
                blockMessage('danger', '1000', result.message);
            }
        }
    });
});

function forgorPassword() {
    blockMessage('', '0', 'Please wait');
    var formData = $("#getinForm").serialize();
    $.ajax({
        url: window.location.origin + "/actions/forgorPassword.php",
        type: "POST",
        data: formData,
        dataType: 'JSON',
        success: function(data) {
            var result = JSON.parse(JSON.stringify(data));
            if (result.status == 1) {
                blockMessage('success', '2000', result.message);
            } else {
                blockMessage('danger', '1000', result.message);
            }
        }
    });
}