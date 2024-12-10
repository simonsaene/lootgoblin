<div class="row-cols-lg-auto g-*">
    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-row align-items-center flex-wrap align-content-center justify-content-center">
        <div class="col-1 form-floating">
            <input type="number" id="hours_filter" name="hours_filter" class="form-control" value="{{ request()->get('hours_filter') }}">
            <label for="hours_filter" class="form-label">Hours</label>
        </div>

        <div class="col-4 form-floating">
            <input type="number" id="silver_filter" name="silver_filter" class="form-control" value="{{ request()->get('silver_filter') }}">
            <label for="silver_filter" class="form-label">Silver</label>
        </div>

        <div class="col-2 form-floating">
            <select name="has_image" id="has_image" class="form-control">
                <option value="" @if(request()->get('has_image') == '') selected @endif>Any</option>
                <option value="1" @if(request()->get('has_image') == '1') selected @endif>Yes</option>
                <option value="0" @if(request()->get('has_image') == '0') selected @endif>No</option>
            </select>
            <label for="has_image" class="form-label">Has Image</label>
        </div>

        <div class="col-2 form-floating">
            <select name="has_video" id="has_video" class="form-control">
                <option value="" @if(request()->get('has_video') == '') selected @endif>Any</option>
                <option value="1" @if(request()->get('has_video') == '1') selected @endif>Yes</option>
                <option value="0" @if(request()->get('has_video') == '0') selected @endif>No</option>
            </select>
            <label for="has_video" class="form-label">Has Video</label>
        </div>

        <div class="col-1 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
        </div>
    </form>
</div>