@once
@push('scripts')
<script>
document.querySelectorAll('form[data-autosubmit] select').forEach(function (el) {
    el.addEventListener('change', function () { el.form.submit(); });
});
</script>
@endpush
@endonce
