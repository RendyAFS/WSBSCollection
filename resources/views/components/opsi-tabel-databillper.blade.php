<div class="d-flex justify-content-left">
    <a href="{{ route('edit-billpers', ['id' => $billper->id]) }}" class="btn btn-blue btn-sm me-2 shadow">
        <i class="bi-pencil"></i>
    </a>
    <form action="{{ route('destroy-billpers', ['id' => $billper->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm me-2 btn-delete shadow">
            <i class="bi-trash"></i>
        </button>
    </form>
</div>
