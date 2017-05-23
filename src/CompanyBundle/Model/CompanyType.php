<?php

namespace CompanyBundle\Model;

use CompanyBundle\Model\om\BaseCompanyType;

class CompanyType extends BaseCompanyType
{
    const BAR_ID = 1;
    const BAR_NAME = "COMPANY_TYPE.BAR.NAME";
    const BAR_DESCRIPTION = "COMPANY_TYPE.BAR.DESCRIPTION";

    const SERVICE_ID = 2;
    const SERVICE_NAME = "COMPANY_TYPE.SERVICE.NAME";
    const SERVICE_DESCRIPTION = "COMPANY_TYPE.SERVICE.DESCRIPTION";

    /**
     * getCompanyTypeListArray()
     * @return mixed
     */
    static public function getCompanyTypeListArray()
    {
        $types = CompanyTypeQuery::create()
            ->orderByName('ASC')
            ->find();

        $typeArr['company_type'] = $types->toArray();
        return $typeArr;
    }
}
