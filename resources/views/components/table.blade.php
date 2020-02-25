<div class="table-responsive">

    <table id="{{ $id }}" class="table table-striped table-hover">

        <thead>
            @foreach ($thead as $item)
                <th scope="col">{{ $item }}</th>
            @endforeach
            <th scope="col" class="text-center">Actions</th>
        </thead>

        <tbody>

        </tbody>

    </table>

</div>
