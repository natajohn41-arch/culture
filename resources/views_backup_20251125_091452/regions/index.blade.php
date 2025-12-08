@extends('layout')

@section('Content')
<main class="app-main">

    <div class="app-content">
        <div class="container-fluid">

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Liste des régions</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                <th>Localisation</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($regions as $region)
                                <tr>
                                    <td>{{ $region->id_region }}</td>
                                    <td>{{ $region->nom_region }}</td>
                                    <td>{{ $region->description }}</td>
                                    <td>{{ $region->population }}</td>
                                    <td>{{ $region->superficie }}</td>
                                    <td>{{ $region->localisation }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Aucune région enregistrée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</main>
@endsection
