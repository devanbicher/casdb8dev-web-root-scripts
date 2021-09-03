<?php 
$entities = [];
// Load All nodes.
$result = \Drupal::entityQuery('node')->execute();
$entity_storage = \Drupal::entityTypeManager()->getStorage('node');
$entities = array_merge($entities, $entity_storage->loadMultiple($result));


// Update URL aliases.
foreach ($entities as $entity) {
\Drupal::service('pathauto.generator')->updateEntityAlias($entity, 'update');
}

echo("Done updating aliases\n");

echo("Clearing duplicate aliases\n");

echo("loading all path alises\n");
$path_result = \Drupal::entityQuery('path_alias')->execute();  
$path_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
$path_multiple = $path_storage->loadMultiple($path_result);

echo("Number of path aliases:  ".strval(count($path_multiple))."\n");

$unique_aliases = [];

foreach($path_multiple as $pid => $one_path){
    $path_values = $one_path->toArray();
    $path_alias = $path_values['alias'];
    $path_node = $path_values['path'];
    if(!in_array($path_alias, $unique_aliases))  {
        $unique_aliases[$path_alias] = [$path_node,$pid];
    }
    
    //$new_path = PathAlias::create($one_path->toArray());
    // $new_path->save();
}

foreach($unique_aliases as $alias => $values){
    $path_node = $values[0];
    $pid = $values[1];

    $paths_w_alias = Drupal::entityQuery('path_alias')
    ->condition('alias',$alias,'=')
    ->condition('path',$path_node,'=')
    ->condition('id',$pid,'<>')
    ->execute();

    print_r($paths_w_alias);
    
    //query
    //alias
    //not pid
    //if alias and path are the same
    //delete the entity

}