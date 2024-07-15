<div class="d-flex justify-content-left">
    <a href="{{ route('edit-tempdatamasters', ['id' => $masters->id]) }}" class="btn border border-0">
        <div class="d-flex align-items-center text-blue">
            <i class="bi bi-pencil-square fs-5"></i>
        </div>
    </a>
    <form action="{{ route('destroy-tempdatamasters', ['id' => $masters->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn border border-0 btn-delete text-red">
            <div class="d-flex align-items-center text-red">
                <i class="bi bi-trash-fill fs-5 "></i>
        </button>
    </form>
</div>
