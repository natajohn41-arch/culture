 <form action="{{ route('langues.store') }}" method="POST">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                      <div class="row mb-3">
                        <label for="code_langue" class="col-sm-2 col-form-label">Code</label>
                        <div class="col-sm-10">
                          <input name="code_langue" value="{{ old('code_langue') }}" type="text" class="form-control" id="code_langue" />
                          @error('code_langue')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                      </div>
                      <div class="row mb-3">
                        <label for="nom_langue" class="col-sm-2 col-form-label">Nom</label>
                        <div class="col-sm-10">
                          <input name="nom_langue" value="{{ old('nom_langue') }}" type="text" class="form-control" id="nom_langue" required />
                          @error('nom_langue')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                          <input name="description" value="{{ old('description') }}" type="text" class="form-control" id="description" />
                          @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                      </div>
 
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                      <a href="{{ route('langues.index') }}" class="btn btn-warning" title="Annuler" aria-label="Annuler">
                        <i class="bi bi-x-lg"></i>
                      </a>
                      <button type="submit" class="btn btn-primary float-end" title="Enregistrer" aria-label="Enregistrer">
                        <i class="bi bi-save"></i>
                      </button>
                    </div>
                    <!--end::Footer-->
                  </form>