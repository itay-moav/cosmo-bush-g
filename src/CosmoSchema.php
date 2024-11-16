<?php
declare(strict_types=1);
namespace DirectorMoav\CosmoBushG;

/**
 * @param array $schema
 * This class can recieve an array of field names and the cosmo actions to be applied to each field.
 * Or,
 * You can use CosmoSchemaBuilder to construct this array.
 * 
 * 
 * @param array  $schema_level_actions=[]
 * This Array of CosmoActions will be implemented to all fields in the schema, 
 * inserted into position $schema_level_rules_at_position
 * If this is one field's schema "field name" => [cosmo_action1(),cosmo_action2(),cosmo_action3(),cosmo_action4()]
 * This is the schema_level_actions  [cosmo_actionA(),cosmo_actionB()]
 * This is schema_level_actions_at_position = 2
 * The final CosmoActions applied to the field will be [cosmo_action1(),cosmo_action2(),cosmo_actionA(),cosmo_actionB(),cosmo_action3(),cosmo_action4()]
 * The default is at the beginning (schema_level_actions_at_position=0)
 * 
 * 
 * @param int $schema_level_actions_at_position=0 
 * See above how to use this parameter
 */
class CosmoSchema{
    
   /**
    * @param array<string,callable[]> $schema
    * @param callable[] $schema_level_actions
    * @param int $schema_level_actions_at_position defaults to 0
    * @return CosmoSchema
    */
    static public function init_cosmo_bush_g(   array $schema,
                                                array $schema_level_actions=[],
                                                int $schema_level_actions_at_position=0):CosmoSchema{
        return new CosmoSchema($schema,$schema_level_actions,$schema_level_actions_at_position);
    }

   /**
    * @param array<string,callable[]> $schema
    * @param callable[] $schema_level_actions
    * @param int $schema_level_actions_at_position defaults to 0
    */
    public function __construct(
                                private array $schema,
                                private array $schema_level_actions=[],
                                private int $schema_level_actions_at_position=0){

        //Validate data - making sure, if there are schema level actions, that the selected insert position exists.
        if(count($schema_level_actions) > 0){
            foreach($schema as $field_cosmo_actions){
                $c = count($field_cosmo_actions);
                if($c-1 < $schema_level_actions_at_position){
                    throw new \LogicException("You have schema_level_actions_at_position [{$schema_level_actions_at_position}] but array of actions is only [{$c}]");
                }
            }
        }
    }
   
   /**
    * The field names of the output array
    * Taken from the input schema field names
    *
    * @return string[]
    */
    public function field_list():array{
        return array_keys($this->schema);
    }
   
   /**
    * Returns the array of cosmo_actions of a specific field + the schema_level_cosmo_actions() for the input field.
    * 
    * @param string $field_name
    * @return callable[]
    */
    public function cosmo_actions_for_field(string $field_name):array{
        if(!isset($this->schema[$field_name])){
            throw new \Exception("No such field [{$field_name}] in cosmo schema");
        }

        $field_schema = $this->schema[$field_name];
        //I am going to insert the global schema parts in the position specified (0 is the default)
        //The way this is written, even if the field has no schema, it will get the global schema
        if($this->schema_level_actions){
            array_splice($field_schema,$this->schema_level_actions_at_position,0,$this->schema_level_actions);
        }
        return $field_schema;
    }
   
   /**
    * Overwrites a previously entered set of rules for the input field
    * It will also set it, if it was not here before.
    * 
    * @param string $field_name
    * @param array<callable> $cosmo_actions
    */
    public function set_or_update_field(string $field_name,array $cosmo_actions):void{
        $this->schema[$field_name] = $cosmo_actions;
    }

   /**
    * @param string $field_name
    * @param callable $cosmo_action
    */
    public function add_cosmo_action_to_field(string $field_name,callable $cosmo_action):void{
        if(!isset($this->schema[$field_name])){
            throw new \Exception("No such field [{$field_name}] in cosmo schema");
        }
        $this->schema[$field_name][] = $cosmo_action;
    }
}
