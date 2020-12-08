<?php

namespace Modules\Pages\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use PawelMysior\Publishable\Publishable;

class Page  extends BaseModel
{
    use LogsActivity, Publishable;
    protected static $logName = 'pages';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'slug','description', 'content', 'type', 'offer_text', 'banner_image', 'meta_title', 'meta_keywords', 'meta_description', 'meta_og_image', 'meta_og_url','status','created_by_name', 'updated_by', 'deleted_by', 'parent', 'parent_title','draft_parent','show_in_menu'];

    protected $fillable = ['name', 'slug','description', 'content', 'type', 'offer_text', 'banner_image', 'meta_title', 'meta_keywords', 'meta_description', 'meta_og_image', 'meta_og_url','status','created_by_name', 'updated_by', 'deleted_by', 'parent', 'parent_title','draft_parent','show_in_menu','banner_image_mob'];

    public function children()
    {
        return $this->hasMany('Modules\Pages\Entities\Page', 'parent');
    }

    public function get_parent()
    {
        return $this->belongsTo('Modules\Pages\Entities\Page', 'parent', 'id');
    }

    public static function checkDraftPage($page_id)
    {
        $page = Page::where('draft_parent', $page_id)->where('deleted_at', null)->where('status', 0)->first();
        return $page;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1)->get();
        // return $query->where('status', 1)->where('show_in_menu', 1)->get();
    }

    

    public function scopeShowInMenu($query)
    {
        // return $query->where('show_in_menu', 1)->get();
        return $query->where('status', 1)->where('show_in_menu', 1)->get();
    }

    public function scopeAboutPage($query)
    {
        return $query->findOrFail(6);
    }

    /**
     * Set the 'meta title'.
     * If no value submitted use the 'Title'.
     *
     * @param [type]
     */
    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = trim(ucwords($value));

        if (empty($value)) {
            $this->attributes['meta_title'] = trim(ucwords($this->attributes['name']));
        }
    }


    public function setCreatedByNameAttribute($value)
    {
        $this->attributes['created_by_name'] = trim(label_case($value));

        if (empty($value)) {
            $this->attributes['created_by_name'] = auth()->user()->name;
        }
    }


    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = trim(strtolower($value));

        if (empty($value)) {
            $this->attributes['slug'] = trim(strtolower(str_replace(' ','-',$this->attributes['name'])));
        }
    }


    /**
     * Set the 'meta description'
     * If no value submitted use the default 'meta_description'.
     *
     * @param [type]
     */
    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['meta_description'] = $value;

        if (empty($value)) {
            $this->attributes['meta_description'] = config('settings.meta_description');
        }
    }

    /**
     * Set the meta meta_og_image
     * If no value submitted use the 'Title'.
     *
     * @param [type]
     */
    public function setMetaOgImageAttribute($value)
    {
        $this->attributes['meta_og_image'] = $value;

        if (empty($value)) {
            if (isset($this->attributes['featured_image'])) {
                $this->attributes['meta_og_image'] = $this->attributes['featured_image'];
            } else {
                $this->attributes['meta_og_image'] = setting('meta_image');
            }
        }
    }

    public function getStatusFormattedAttribute()
    {
        switch ($this->status) {
            case '0':
                return '<span class="badge badge-warning">Draft</span>';
                break;

            // case '1':
            //     return '<span class="badge badge-success">Published</span>';
            //     break;

            default:
                return '';
                break;
        }
    }  

    public function register_url($setting_value)
    {
        if(isset($_GET['a'])){
            $register_url = 'https://quizingo.co.uk/client/#/registration?a='.$_GET['a'];
        }else{
            $register_url = 'https://quizingo.co.uk/client/#/registration';
        }

        $change_from = array('#$REG_URL$');
        $change_to = array($register_url);
        return str_replace($change_from, $change_to, $setting_value);

        // return $register_url;
    }

    public function scopeContent($query)
    {
        $this->content = Self::register_url($this->content);
        return $this->content;
        // $content = $this->content;
        // $change_from = array('#$REG_URL$');
        // $change_to = array(Self::register_url());
        // $content = str_replace($change_from, $change_to, $content);
        // return $content;
    }

    public function scopeDescription($query)
    {
        $this->description = Self::register_url($this->description);
        return $this->description;
    }

    public function scopeOfferText($query)
    {
        $this->offer_text = Self::register_url($this->offer_text);
        return $this->offer_text;
    }

}

