<div class="d-flex justify-content-left">
    <form action="{{ route('destroy-tempalls', ['id' => $tempall->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn border border-0 btn-delete text-red">
            <div class="d-flex align-items-center text-red">
                <i class="bi bi-trash me-1 "></i>
                Hapus
        </button>
    </form>
</div>
