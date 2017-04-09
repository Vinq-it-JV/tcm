<?php

namespace CompanyBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'company_address' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.CompanyBundle.Model.map
 */
class CompanyAddressTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CompanyBundle.Model.map.CompanyAddressTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('company_address');
        $this->setPhpName('CompanyAddress');
        $this->setClassname('CompanyBundle\\Model\\CompanyAddress');
        $this->setPackage('src.CompanyBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('company_id', 'CompanyId', 'INTEGER' , 'company', 'id', true, null, null);
        $this->addForeignPrimaryKey('address_id', 'AddressId', 'INTEGER' , 'address', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_ONE, array('company_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Address', 'UserBundle\\Model\\Address', RelationMap::MANY_TO_ONE, array('address_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // CompanyAddressTableMap
