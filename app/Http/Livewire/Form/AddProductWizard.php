<?php

namespace App\Http\Livewire\Form;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\HelperFunctions\StringToCamelCase;

class AddProductWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    public $product_model, $product_brand, $product_brand_type, $product_title, $product_valued_minimum, $product_valued_maximum, $product_ticketed_sale_price,$product_details, $product_search_reference, $product_image_title, $product_image_file_path, $product_new_image_file_path = "";

    public $product_departments = ['Accessories','Books','Clothing','Homewares','Linen','Miscellaneous'
        ,'Shoes','Sport/Hobby','Toys/Games',
    ];

    public $product_types =[];

    public $clothing_types = [
        'Blouse','Cardigan','Coat','Dress','Jacket','Jeans','Jumper','Leggings',
        'Pants','Poncho','Shirt','Shorts','Skirt','Suit','Swimsuit','Tracksuit','Trousers','T-Shirt','Waistcoat'
    ];

    public $accessory_types = ['Belt','Clutch','Handbag','Hat','Phone Case','Shawl','Scarf','Sunglasses','Tie','Travel Bag','Wallet'
    ];

    public $shoe_types = ['Boots','Flats','Heels','Kids','Sport'];

    public $homeware_types = ['Bowl','Crystalware','Cutlery','Dinner Set','Glassware','Cup','Cup Set','Plate','Ornament','Vase'];

    public $toy_game_types = ['Antique','Board Game','Collectable','Doll','Jigsaw','Playset','Soft'];

    public $book_types = ['Antique','Collectable'];

    public $linen_types = ['Bedding','Blanket','Doona','Sheet Set'];

    public $miscellaneous_tpes = ['Collectable'];
 
    public $sport_hobby_types = ['Sport Equipment','Musical Instruments'];

    public $successMsg = '';

    // =====================================================

    public function updated()
    {
        if ($this->product_model == 'Accessories') {
            $this->product_types = $this->accessory_types;
        }elseif($this->product_model == 'Books') {
            $this->product_types = $this->book_types;
        }elseif($this->product_model == 'Clothing') {
            $this->product_types = $this->clothing_types;
        }elseif($this->product_model == 'Homewares') {
            $this->product_types = $this->homeware_types;
        }elseif($this->product_model == 'Linen') {
            $this->product_types = $this->linen_types;
        }elseif($this->product_model == 'Miscellaneous') {
            $this->product_types = $this->miscellaneous_tpes;
        }elseif($this->product_model == 'Shoes') {
            $this->product_types = $this->shoe_types;
        }elseif($this->product_model == 'Sport/Hobby') {
            $this->product_types = $this->sport_hobby_types;
        }elseif($this->product_model == 'Toys/Games') {
            $this->product_types = $this->toy_game_types;
        }
    }

    public function render()
    {
        return view('livewire.form.add-product-wizard');
    }

    public function firstStepSubmit()
    {

        $validatedData = $this->validate([
            'product_model' => 'required',
            'product_brand' => 'required',
            'product_brand_type' => 'required'
        ]);
        // dd($validatedData);
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $validatedData = $this->validate([
            'product_valued_minimum' => 'required_if:product_valued_maximum,=,null',
            'product_valued_maximum' => 'required_if:product_valued_minimum,=,null',
            'product_ticketed_sale_price' => 'required'
        ]);
//   'detail' => 'required',
        $this->currentStep = 3;
    }

    public function thirdStepSubmit()
    {
        $validatedData = $this->validate([
            'product_details' => 'required',
            'product_search_reference' => 'nullable'
        ]);
        // dd($validatedData);

        $this->currentStep = 4;
    }

    public function fourthStepSubmit()
    {
        $validatedData = $this->validate([
            'product_image_title' => 'nullable|required_with:product_image_file_path',
            'product_image_file_path' => 'nullable|required_with:product_image_title|mimes:jpg,jpeg,png|max:5048'
        ]);
        // dd($validatedData);

        if ($this->product_image_title == null && $this->product_image_file_path == null) {
            $this->product_new_image_file_path = "noImage.jpg";
            $this->currentStep = 5;
        } else {
            $camelCase = new StringToCamelCase();
            $newImageTitle = $camelCase->camelCase($this->product_image_title);
            // dd($newImageTitle);
            $imagePath = $this->product_image_file_path->getFileName();
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            // dd($imagePath);
            // dd($extension);
            $this->product_new_image_file_path = date('d-m-y') . '-' . $newImageTitle . '.' . $extension;
            // dd($newImageFilePath);
            $this->product_image_file_path->storeAs('images', $this->product_new_image_file_path, 'public');

            $this->currentStep = 5;
        }
        
    }



    public function submitForm()
    {
        $this->product_model::create([
            'name' => $this->name,
            'price' => $this->price,
            'detail' => $this->detail,
        ]);
  
        $this->successMsg = 'Product successfully created.';
  
        $this->clearForm();
  
        $this->currentStep = 1;
    }

    public function back($step)
    {
        $this->currentStep = $step;    
    }

    public function clearForm()
    {
        $this->name = '';
        $this->price = '';
        $this->detail = '';
    }
}
