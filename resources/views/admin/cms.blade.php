@extends('layouts.app')

@section('content')
<!-- SECTION G: CONTENT CUSTOMIZATION PANEL (CMS) -->
<div id="panel-cms" class="admin-panel bg-white p-4 rounded-4 border">
  <h2>Content Customization Panel (CMS)</h2>
  <p class="text-secondary fs-09 mb-4">Configure public-facing templates and static legal document sections.</p>

  <form onsubmit="saveCMSContent(event)">
    <div class="row g-4">
      <div class="col-md-6">
        <label class="form-label fw-bold small">Rewrite Public FAQs</label>
        <textarea class="form-control form-control-sm" rows="3" id="cms-faqs" required>Q1: How do I submit a nomination?
A1: Click nominations tab, enter email, details and press apply.

Q2: Who is eligible for hospitality events?
A2: All active full-time platform users mapped in the directory.</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold small">Modify Informational "About Us" Content</label>
        <textarea class="form-control form-control-sm" rows="3" id="cms-about" required>Nomination Management System is deployed and managed by Events COE to simplify organizational delegation of corporate events.</textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-bold small">Privacy Policies & Legal Notices</label>
        <textarea class="form-control form-control-sm" rows="4" id="cms-privacy" required>Privacy Notice:
All information collected is processed in compliance with organization IT policies and enterprise boundary access controls.</textarea>
      </div>
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary btn-sm bg-primary px-4">Publish Content Changes</button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function saveCMSContent(e) {
    e.preventDefault();
    showToast("CMS content successfully saved and published.");
}
</script>
@endpush
