# cosmo-bush-g
## A PHP validation and transformation library with a functional interface.
`"my age field name" => [required(),is_numeric(),to_float(),max(120.0),min(18.0)]`

## Language definitions
- ActionBush : A functional component. Recieves input and either validate it to be somethin or transform it to something else. If an Action is part of a sorted list, it passes the output as the input for the next Action.  
- CosmoFieldschema : associative array member where the field name as key and an ordered list of ActionBushs to be applied to that field. ` ['field name'=>[ActionBush1(), ActionBush2()...ActionBushN()]]`
- CosmoSchema : The collection of CosmoFieldschema to be used to transform and validate an input (json/array/itterable etc)
```
$my_form_schema = [
    'field name1'=>[ActionBush1(), ActionBush2()...ActionBushN()],
    'field name2'=>[ActionBush1(), ActionBush2()...ActionBushN()],
    'field name3'=>[ActionBush1(), ActionBush2()...ActionBushN()],
    ....
    'field nameN'=>[ActionBush1(), ActionBush2()...ActionBushN()]
]
```
- GApplier : applies the schema to the input. The out of the applier is either a list of errors or an associative array (defined by the schema).  
- CosmoBushG : The Main of this lib, holds shortcuts and ways to manage the entire flow from one place.
