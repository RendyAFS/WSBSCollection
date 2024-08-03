<div class="d-flex justify-content-left">
    <form action="{{ route('destroy-tempbillpers', ['id' => $tempbillper->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn border border-0 btn-delete text-red">
            <div class="d-flex align-items-center text-red">
                <i class="bi bi-trash-fill fs-5"></i>
        </button>
    </form>
</div>
