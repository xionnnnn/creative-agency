// Custom JavaScript for Lens Creative Agency

$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Smooth scroll for anchor links
    $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
            && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 70
                }, 1000);
            }
        }
    });
    
    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    
    $('#back-to-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 600);
        return false;
    });
    
    // Form validation
    $('form').submit(function() {
        var valid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return valid;
    });
    
    // Auto-hide alerts after 5 seconds
    $('.alert').not('.alert-permanent').delay(5000).slideUp();
    
    // Password strength checker
    $('#password, #new_password').on('keyup', function() {
        var password = $(this).val();
        var strength = 0;
        
        if (password.length >= 6) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[$@#&!]+/)) strength += 1;
        
        var meter = $('#password-strength');
        if (meter.length) {
            switch(strength) {
                case 0:
                case 1:
                    meter.html('Weak').css('color', 'red');
                    break;
                case 2:
                case 3:
                    meter.html('Medium').css('color', 'orange');
                    break;
                case 4:
                case 5:
                    meter.html('Strong').css('color', 'green');
                    break;
            }
        }
    });
    
    // Confirm password match
    $('#confirm_password, #new_password, #confirm_new_password').on('keyup', function() {
        var password = $('#new_password').val();
        var confirm = $('#confirm_new_password').val();
        
        if (password != confirm) {
            $('#confirm_new_password').addClass('is-invalid');
            $('#password-match').html('Passwords do not match').css('color', 'red');
        } else {
            $('#confirm_new_password').removeClass('is-invalid');
            $('#password-match').html('Passwords match').css('color', 'green');
        }
    });
    
    // Image preview before upload
    $('#profile_picture, #package_image, #gallery_image').change(function() {
        var input = this;
        var preview = $(this).data('preview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $(preview).attr('src', e.target.result).show();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    });
    
    // Search functionality
    $('#search-input').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        
        $('.searchable-item').each(function() {
            var itemText = $(this).text().toLowerCase();
            if (itemText.indexOf(searchText) === -1) {
                $(this).closest('.col-md-4, .col-md-6, .col-md-3').hide();
            } else {
                $(this).closest('.col-md-4, .col-md-6, .col-md-3').show();
            }
        });
    });
    
    // Loading spinner
    $(document).ajaxStart(function() {
        $('body').append('<div class="spinner-wrapper"><div class="spinner"></div></div>');
    }).ajaxStop(function() {
        $('.spinner-wrapper').remove();
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    var toastHtml = `
        <div class="toast-container">
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(toastHtml);
    var toast = new bootstrap.Toast($('.toast').last());
    toast.show();
    
    setTimeout(function() {
        $('.toast-container').remove();
    }, 5000);
}

// Confirmation dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Format currency
function formatCurrency(amount) {
    return '₱' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Get URL parameters
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}