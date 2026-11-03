@php $suspended = $company->user?->isSuspended(); @endphp
<form method="POST" action="{{ route('panel.admin.company.suspend', $company) }}" class="d-inline">
    @csrf
    <button class="btn btn-sm {{ $suspended ? 'btn-outline-success' : 'btn-outline-warning' }}"
            onclick="return confirm('{{ $suspended ? 'Aktifkan' : 'Nonaktifkan' }} akun {{ addslashes($company->company_name) }}?')">
        {{ $suspended ? 'Aktifkan' : 'Nonaktifkan' }}
    </button>
</form>
@if ($suspended)<span class="badge text-bg-warning ms-1">Nonaktif</span>@endif
