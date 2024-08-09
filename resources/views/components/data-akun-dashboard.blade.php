<div class="d-flex justify-content-between align-items-center">
    <span class="fw-bold">Akun</span>
    <a href="{{ route('reportsalesbillper.index') }}" class="">
        <i class="bi bi-three-dots fw-bold fs-4"></i>
    </a>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Satatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th class="fw-normal">
                    {{ Str::of($user->name)->explode(' ')->map(function ($word, $index) {
                            return $index === 0 ? $word : Str::substr($word, 0, 1) . '.';
                        })->implode(' ') }}
                </th>
                <th>
                    <span class="badge {{ $user->status == 'Aktif' ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ $user->status }}
                    </span>
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
