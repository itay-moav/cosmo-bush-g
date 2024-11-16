<?php
declare(strict_types=1);
namespace DirectorMoav\CosmoBushG;




/**
 * 
 * @author itay
 * @date 2022-11-21
 * 
 *  Takes the CosmoSchema and runs all the action in it on the input collection.
 */
class CosmoRunner{
    
    /**
     * The CosmobushApply go over the CosmoSchema, and if it passed all checks and filters
     * turns it to true
     *
     * @var boolean
     */
    public bool $validCosmobushCollection = false;
    
    /**
     * @var array<string,mixed>
     */
    private array $normalized_collection;
    
    /**
     * @var CosmoSchema
     */
    private CosmoSchema $CosmoSchema;
    
    /**
     * Errors per field
     * @var array<string,string[]>
     */
    private array $CosmoBushFieldsErrors = [];
    
    /**
     *
     * @param CosmoSchema $CosmoSchema
     * @param array<int|string,mixed>|\stdClass $collection
     */
    public function __construct(CosmoSchema $CosmoSchema,$collection){
        $normalized_collection = $collection;
        if(is_object($collection)){
            $normalized_collection = (array)$collection;
        }
        if(!is_array($normalized_collection)){
            throw new \LogicException('Input collection has to be array or \stdClass');
        }
        $this->normalized_collection = $normalized_collection;
        $this->CosmoSchema = $CosmoSchema;
    }

    /**
     * Apply all actions to the field by order in CosmoSchema
     * does not modify original collection
     *
     * @param string $field_name
     * @return mixed it really depends on what transformer function used here
     */
    public function apply_cosmobush_actions_to_field(string $field_name):mixed{
        $actions = $this->CosmoSchema->cosmo_actions_for_field($field_name);
        $mutated_value = $this->normalized_collection[$field_name] ?? null; //normalizing unset to null
        $this->CosmoBushFieldsErrors[$field_name]=[];
        
        foreach($actions as $action){
            try{
                $mutated_value = $action($mutated_value,$field_name,$this->normalized_collection);
            }catch(Actions\CosmoValidationException $e){
                $this->validCosmobushCollection = false;
                $this->CosmoBushFieldsErrors[$field_name][]=$e->getMessage();
                //bail out with original value
                return $this->normalized_collection[$field_name] ?? null;
            }
        }
        return $mutated_value;
    }
    
    
    /**
     * Apply all Cosmo Bush Actions to all fields
     * @return array<string,mixed> cosmobush collection in associative array format
     */
    public function run_cosmobush():array{
        $this->validCosmobushCollection = true;
        $cosmobush_collection = [];
        try{
            $field_list = $this->CosmoSchema->field_list();
            foreach($field_list as $field_name){
                $cosmobush_collection[$field_name] = $this->apply_cosmobush_actions_to_field($field_name);
            }
        }catch(\Error $e){
            $this->validCosmobushCollection = false;
            throw $e;
        }
        return $cosmobush_collection;
    }
    
    /**
     * @return array<string,string[]>
     */
    public function get_errors():array{
        return $this->CosmoBushFieldsErrors;
    }
}


