<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h1>Page de Post</h1>
            <form action="/post" method="post" enctype="multipart/form-data">
                <input type="file"  name="image" id="image" @change="handleFileChange"/>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>  
    </div>
</div>