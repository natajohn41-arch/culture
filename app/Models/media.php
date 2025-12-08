<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'id_media';
    
   
    protected $fillable = [
        'chemin',
        'description',
        'id_contenu',
        'id_type_media',
        'taille',
        'mime_type'
    ];

    // Relation avec le contenu
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    // Relation avec le type de média
    public function typeMedia()
    {
        return $this->belongsTo(TypeMedia::class, 'id_type_media');
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->whereHas('typeMedia', function($q) {
            $q->where('nom_media', 'Image');
        });
    }

    public function scopeVideos($query)
    {
        return $query->whereHas('typeMedia', function($q) {
            $q->where('nom_media', 'Vidéo');
        });
    }

    public function scopeAudios($query)
    {
        return $query->whereHas('typeMedia', function($q) {
            $q->where('nom_media', 'Audio');
        });
    }

    public function scopeDocuments($query)
    {
        return $query->whereHas('typeMedia', function($q) {
            $q->where('nom_media', 'Document');
        });
    }

    // Accessors
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin);
    }

    /**
     * Accessor pour obtenir le type MIME, en le détectant si nécessaire
     * Note: Ne modifie pas la base de données, retourne juste la valeur détectée
     */
    public function getMimeTypeAttribute($value)
    {
        // Si le mime_type est déjà défini, le retourner
        if (!empty($value)) {
            return $value;
        }

        // Sinon, essayer de le détecter depuis le fichier
        if ($this->chemin && $this->fichierExiste()) {
            $fullPath = storage_path('app/public/' . $this->chemin);
            if (file_exists($fullPath)) {
                return mime_content_type($fullPath);
            }
        }

        // Dernier recours : détecter depuis l'extension
        $extension = pathinfo($this->chemin, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * Obtient le type MIME, en le détectant si nécessaire
     */
    public function getMimeTypeOuDetecte()
    {
        // Si le mime_type est déjà défini, le retourner
        if (!empty($this->attributes['mime_type'] ?? null)) {
            return $this->attributes['mime_type'];
        }

        // Sinon, essayer de le détecter depuis le fichier
        if ($this->chemin && $this->fichierExiste()) {
            $fullPath = storage_path('app/public/' . $this->chemin);
            if (file_exists($fullPath)) {
                return mime_content_type($fullPath);
            }
        }

        // Dernier recours : détecter depuis l'extension
        $extension = pathinfo($this->chemin, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    public function getEstImageAttribute()
    {
        return $this->typeMedia && $this->typeMedia->nom_media === 'Image';
    }

    public function getEstVideoAttribute()
    {
        return $this->typeMedia && $this->typeMedia->nom_media === 'Vidéo';
    }

    public function getEstAudioAttribute()
    {
        return $this->typeMedia && $this->typeMedia->nom_media === 'Audio';
    }

    public function getEstDocumentAttribute()
    {
        return $this->typeMedia && $this->typeMedia->nom_media === 'Document';
    }

    public function getTailleFormateeAttribute()
    {
        if (file_exists(storage_path('app/public/' . $this->chemin))) {
            $taille = filesize(storage_path('app/public/' . $this->chemin));
            
            if ($taille >= 1048576) {
                return round($taille / 1048576, 2) . ' MB';
            } elseif ($taille >= 1024) {
                return round($taille / 1024, 2) . ' KB';
            } else {
                return $taille . ' bytes';
            }
        }
        
        return 'N/A';
    }

    public function getExtensionAttribute()
    {
        return pathinfo($this->chemin, PATHINFO_EXTENSION);
    }

    /**
     * Vérifie si le fichier média existe dans le stockage
     */
    public function fichierExiste()
    {
        return Storage::disk('public')->exists($this->chemin);
    }

    /**
     * Retourne l'URL du média si le fichier existe, sinon une URL par défaut
     */
    public function getUrlOuDefaut($urlDefaut = null)
    {
        if ($this->fichierExiste()) {
            return asset('storage/' . $this->chemin);
        }
        
        return $urlDefaut ?? 'https://via.placeholder.com/400x250?text=Media+non+disponible';
    }
}