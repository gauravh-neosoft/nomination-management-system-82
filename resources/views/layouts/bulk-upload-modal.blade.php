<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="bulkUploadLabel">
          <i class="bi bi-upload text-primary me-2"></i>Bulk Upload Nominations
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <p class="text-secondary mb-3">
          Select or drag a completed nomination template Excel/CSV file to upload and submit nominations for <strong id="bulk-upload-event-name-text">this event</strong>.
        </p>

        <!-- Upload Form Area -->
        <form id="bulkUploadForm" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="event_id" id="bulkUploadEventId" value="">

          <div 
            class="upload-file rounded-4 d-flex flex-column gap-2 align-items-center py-5 border-dashed" 
            style="border: 2px dashed #cbd5e1; background-color: #f8fafc; cursor: pointer;"
            id="dragDropArea"
          >
            <i class="bi bi-file-earmark-excel text-success fs-1 mb-2"></i>
            <p class="fw-semibold mb-0">Drag and drop your file here, or click to browse</p>
            <p class="text-small text-muted mb-0">Supports Excel (.xlsx, .xls) and CSV (.csv)</p>
            <input type="file" id="bulkUploadFileInput" name="file" accept=".xlsx, .xls, .csv" style="display: none;">
          </div>
        </form>

        <!-- Progress and Results Area -->
        <div id="bulkUploadResults" class="mt-4" style="display: none;">
          <!-- Will be populated dynamically by AJAX response -->
        </div>
      </div>
      <div class="modal-footer border-top justify-content-between">
        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary rounded-3 bg-primary" id="btnBrowseFile">
          Browse File
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bulkUploadModalEl = document.getElementById('bulkUploadModal');
    if (!bulkUploadModalEl) return;

    const modal = new bootstrap.Modal(bulkUploadModalEl);
    const form = document.getElementById('bulkUploadForm');
    const fileInput = document.getElementById('bulkUploadFileInput');
    const dragDropArea = document.getElementById('dragDropArea');
    const resultsContainer = document.getElementById('bulkUploadResults');
    const eventNameText = document.getElementById('bulk-upload-event-name-text');
    const eventIdInput = document.getElementById('bulkUploadEventId');
    const btnBrowse = document.getElementById('btnBrowseFile');

    // Trigger file selection on clicking the area or browse button
    dragDropArea.addEventListener('click', () => fileInput.click());
    btnBrowse.addEventListener('click', () => fileInput.click());

    // Drag and Drop styles
    dragDropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragDropArea.style.borderColor = '#0d6efd';
        dragDropArea.style.backgroundColor = '#f1f5f9';
    });

    dragDropArea.addEventListener('dragleave', () => {
        dragDropArea.style.borderColor = '#cbd5e1';
        dragDropArea.style.backgroundColor = '#f8fafc';
    });

    dragDropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dragDropArea.style.borderColor = '#cbd5e1';
        dragDropArea.style.backgroundColor = '#f8fafc';
        
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFileUpload();
        }
    });

    // File input change
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            handleFileUpload();
        }
    });

    // Populate modal inputs when modal is shown (via relatedTarget button)
    bulkUploadModalEl.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) return;

        const eventId = button.getAttribute('data-event-id');
        const eventName = button.getAttribute('data-event-name');

        if (eventIdInput) eventIdInput.value = eventId;
        if (eventNameText) eventNameText.textContent = eventName;

        // Reset previous results
        resultsContainer.style.display = 'none';
        resultsContainer.innerHTML = '';
        fileInput.value = '';
    });

    function handleFileUpload() {
        const file = fileInput.files[0];
        if (!file) return;

        const eventId = eventIdInput.value;
        if (!eventId) {
            alert('Event context is missing.');
            return;
        }

        const formData = new FormData(form);
        
        resultsContainer.style.display = 'block';
        resultsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p class="text-secondary fw-semibold mb-0">Processing and validating nominations...</p>
                <small class="text-muted">This may take a few seconds.</small>
            </div>
        `;

        const uploadUrl = `/nominator/events/${eventId}/bulk-upload`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Server error'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="card border-0 rounded-4 shadow-sm bg-light-sky-blue p-3 mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                            <h5 class="fw-bold mb-0">Bulk Upload Results</h5>
                        </div>
                        <div class="row text-center row-gap-2">
                            <div class="col-4 border-end">
                                <h4 class="fw-bold mb-0">${data.total}</h4>
                                <small class="text-secondary text-uppercase fw-semibold fs-07">Total Rows</small>
                            </div>
                            <div class="col-4 border-end">
                                <h4 class="fw-bold text-success mb-0">${data.success_count}</h4>
                                <small class="text-secondary text-uppercase fw-semibold fs-07">Applied Successfully</small>
                            </div>
                            <div class="col-4">
                                <h4 class="fw-bold text-danger mb-0">${data.failed_count}</h4>
                                <small class="text-secondary text-uppercase fw-semibold fs-07">Failed</small>
                            </div>
                        </div>
                    </div>
                `;

                if (data.failed_count > 0) {
                    html += `
                        <div class="mt-3">
                            <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Failed Nominees Details:</h6>
                            <div style="max-height: 250px; overflow-y: auto;" class="border rounded-4 p-2 hide-scrollbar">
                                <table class="table table-sm table-hover table-borderless align-middle mb-0 text-normal">
                                    <thead>
                                        <tr class="table-light">
                                            <th style="min-width: 150px">Email / Context</th>
                                            <th>Reason for Failure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;
                    data.failed_list.forEach(item => {
                        html += `
                            <tr class="border-bottom">
                                <td class="text-break py-2 fw-semibold">${item.email}</td>
                                <td class="text-danger py-2">${item.reason}</td>
                            </tr>
                        `;
                    });
                    html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                }

                resultsContainer.innerHTML = html;
                
                // If some nominees succeeded, update tables dynamically or alert to refresh
                if (data.success_count > 0) {
                    if (typeof showToast === 'function') {
                        showToast(`${data.success_count} nomination(s) applied successfully!`, 'bg-success');
                    }
                    bulkUploadModalEl.addEventListener('hidden.bs.modal', function() {
                        window.location.reload();
                    }, { once: true });
                }
            } else {
                resultsContainer.innerHTML = `
                    <div class="alert alert-danger border-0 rounded-4 p-3 d-flex align-items-center gap-2">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                        <p class="mb-0 fw-semibold">${data.message || 'Upload failed.'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultsContainer.innerHTML = `
                <div class="alert alert-danger border-0 rounded-4 p-3 d-flex align-items-center gap-2">
                    <i class="bi bi-x-circle-fill fs-4"></i>
                    <p class="mb-0 fw-semibold">${error.message || 'An error occurred during file upload.'}</p>
                </div>
            `;
        });
    }
});
</script>
@endpush
