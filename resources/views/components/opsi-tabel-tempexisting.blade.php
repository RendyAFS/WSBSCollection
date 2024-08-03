<div class="d-flex justify-content-left">
    <form action="{{ route('destroy-tempexistings', ['id' => $tempexisting->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn border border-0 btn-delete text-red">
            <div class="d-flex align-items-center text-red">
                <i class="bi bi-trash-fill fs-5"></i>
        </button>
    </form>
</div>
