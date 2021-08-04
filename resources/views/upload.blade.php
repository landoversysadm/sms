<form method="POST" action="/upload" id="signup-form" class="signup-form" enctype="multipart/form-data">
    @csrf

    <h2 class="form-title">upload</h2>
    <div class="form-group">
        <label> upload file</label>
        <input type="file" class="form-input" name="upload[]" id="profile_picture" placeholder="select file" multiple  required/>
    </div>

    <button type="submit">Upload</button>
</form>
