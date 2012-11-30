// This code generated by Sds\DoctrineExtensions\Dojo
define([
    'dojo/_base/declare',
    'Sds/Mvc/BaseModel'
],
function(
    declare,
    BaseModel
){
    // Will return an object representing the document
    // which can be serialized to json.

    return declare(
        'Sds/DoctrineExtensions/Test/Dojo/TestAsset/Document/Simple',
        [BaseModel],
        {

            _fields: [
                "id",
                "name",
                "country",
                "camelCaseProperty",
                "_className"
            ],
            _className: 'Sds\\DoctrineExtensions\\Test\\Dojo\\TestAsset\\Document\\Simple',
            _validatorMid: 'Sds/DoctrineExtensions/Test/Dojo/TestAsset/Document/Simple/ModelValidator'
        }
    );
});
