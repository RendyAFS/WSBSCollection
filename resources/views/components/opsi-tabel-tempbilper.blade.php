<div class="d-flex justify-content-left">
    <form action="{{ route('destroy-tempbillpers', ['id' => $tempbillper->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm me-2 btn-delete shadow">
            <i class="bi-trash"></i>
        </button>
    </form>
</div>
