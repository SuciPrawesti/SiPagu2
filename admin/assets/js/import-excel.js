/**
 * Import Excel JavaScript
 */

$(document).ready(function() {
    // Update file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
        
        // Validate file size
        validateFileSize(this);
    });
    
    // Form submission with preview
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return false;
        }
        
        // Show loading overlay
        showLoading();
        
        // Submit form via AJAX for preview
        const formData = new FormData(this);
        formData.append('preview', 'true');
        
        $.ajax({
            url: '../includes/import_excel.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoading();
                
                try {
                    const data = JSON.parse(response);
                    
                    if (data.success) {
                        // Show preview modal
                        showPreview(data.data, data.headers);
                    } else {
                        showError(data.message || 'Error processing file');
                    }
                } catch (e) {
                    showError('Invalid response from server');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showError('Server error: ' + error);
            }
        });
    });
    
    // Confirm import button
    $('#confirmImport').on('click', function() {
        const formData = new FormData($('#importForm')[0]);
        
        // Show loading
        showLoading();
        
        // Submit actual import
        $.ajax({
            url: '../includes/import_excel.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoading();
                $('#previewModal').modal('hide');
                
                try {
                    const data = JSON.parse(response);
                    
                    if (data.success) {
                        showSuccess(data.message);
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        showError(data.message);
                    }
                } catch (e) {
                    showError('Invalid response from server');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showError('Server error: ' + error);
            }
        });
    });
    
    // Functions
    function validateFileSize(fileInput) {
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        const file = fileInput.files[0];
        
        if (file && file.size > maxSize) {
            showError('File size exceeds 5MB limit');
            fileInput.value = '';
            $(fileInput).next('.custom-file-label').html('Choose file...');
            return false;
        }
        
        return true;
    }
    
    function validateForm() {
        const fileInput = document.getElementById('excel_file');
        
        if (!fileInput.files || fileInput.files.length === 0) {
            showError('Please select a file to upload');
            return false;
        }
        
        if (!validateFileSize(fileInput)) {
            return false;
        }
        
        return true;
    }
    
    function showPreview(data, headers) {
        let html = '<div class="table-responsive"><table class="table table-bordered">';
        
        // Header row
        html += '<thead><tr>';
        headers.forEach(header => {
            html += `<th>${header}</th>`;
        });
        html += '</tr></thead>';
        
        // Data rows
        html += '<tbody>';
        data.forEach((row, index) => {
            if (index < 10) { // Show first 10 rows only
                html += '<tr>';
                row.forEach(cell => {
                    html += `<td>${cell || ''}</td>`;
                });
                html += '</tr>';
            }
        });
        html += '</tbody>';
        
        html += '</table></div>';
        
        if (data.length > 10) {
            html += `<p class="text-muted">Showing 10 of ${data.length} rows</p>`;
        }
        
        $('#previewContent').html(html);
        $('#previewModal').modal('show');
    }
    
    function showLoading() {
        if (!$('#loadingOverlay').length) {
            $('body').append(`
                <div class="loading-overlay active" id="loadingOverlay">
                    <div class="loading-spinner"></div>
                </div>
            `);
        } else {
            $('#loadingOverlay').addClass('active');
        }
    }
    
    function hideLoading() {
        $('#loadingOverlay').removeClass('active');
    }
    
    function showError(message) {
        // Create or update error alert
        if (!$('#errorAlert').length) {
            $('#importForm').prepend(`
                <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
        } else {
            $('#errorAlert').html(`
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `).show();
        }
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            $('#errorAlert').alert('close');
        }, 5000);
    }
    
    function showSuccess(message) {
        // Create success alert
        if (!$('#successAlert').length) {
            $('#importForm').prepend(`
                <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
        } else {
            $('#successAlert').html(`
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `).show();
        }
    }
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});