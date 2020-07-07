<?php namespace App\Http\Controllers;
use App\Helpers\LumenHelper;
use App\Models\WpPost;

class ExampleMetaBoxController extends Controller
{
	private $helper, $post, $request;
    /**
     * Create a new controller instance.
     * @param array $metabox_attributes (injected automatically)
     */
    public function __construct(LumenHelper $helper, WpPost $post)
    {
	    $this->helper = $helper;
	    $this->request = $this->helper->request();
	    $this->post = $post;
    }
	public function template($post, $metabox_attributes){
		$post = $this->post->with('meta')->find($post->ID);
	    return $this->helper->view('meta_box', compact('post', 'metabox_attributes'));
    }
	public function save($post, $post_id, $update){

		//The user is allowed to update the post...
    	if( $this->request->filled('lumen_meta_test') && $this->request->user()->can('update-post', $post)) {

    		$this->post = $this->post->with('meta')->find($post_id);

    		if($this->post->meta()->where('meta_key', 'lumen_meta_test')->exists()){
			    $this->post->meta()->where('meta_key', 'lumen_meta_test')->update(array(
				    'meta_value' => $this->request->get('lumen_meta_test')
			    ));
		    }else{
			    $this->post->meta()->create(array(
				    'meta_key' => 'lumen_meta_test',
		            'meta_value' => $this->request->get('lumen_meta_test')
			    ));
		    }

			//$newpost = new WpPost();
			//$newpost->post_title = str_random(16);
			//$newpost->post_name = str_random(16);
			//$newpost->post_author = 1;
			//$newpost->save();
			//$newpost->attachTaxonomy(22);
			//$newpost->detachTaxonomy(22);
	    }
	}

    public function menuMetaBox(){
        return 'Test';
    }
}
