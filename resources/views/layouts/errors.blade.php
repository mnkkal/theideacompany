@if ($errors->any())
    <div class="alert alert-danger">
        <div class="d-flex">
                <span class="fs-7 text-white d-flex align-items-center">
                <i class="fa-solid fa-face-frown me-3"></i>
                </span>
            <div class="d-flex flex-column">
                <span class="fs-7 text-white d-flex align-items-center" role="alert">{{$errors->first()}}</span>
            </div>
        </div>
    </div>
@endif
