fos.Router.setData({"base_url":"","routes":{"administration_dashboard":{"tokens":[["text","\/administration\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_users":{"tokens":[["text","\/administration\/users"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_users_get":{"tokens":[["text","\/administration\/users\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_user_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_user_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_user_add":{"tokens":[["text","\/administration\/user\/add"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_user_new":{"tokens":[["text","\/administration\/user\/new"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_user_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"administration_user_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_user_delete_email":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","emailid"],["text","\/email"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_user_delete_phone":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","phoneid"],["text","\/phone"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_user_delete_address":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","addressid"],["text","\/address"],["variable","\/","[^\/]++","userid"],["text","\/administration\/user"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_companies":{"tokens":[["text","\/administration\/companies"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_companies_get":{"tokens":[["text","\/administration\/companies\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_company_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_company_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_company_add":{"tokens":[["text","\/administration\/company\/add"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_company_new":{"tokens":[["text","\/administration\/company\/new"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_company_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"administration_company_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_company_delete_email":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","emailid"],["text","\/email"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_company_delete_phone":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","phoneid"],["text","\/phone"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_company_delete_address":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","addressid"],["text","\/address"],["variable","\/","[^\/]++","companyid"],["text","\/administration\/company"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_regions":{"tokens":[["text","\/administration\/regions"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_regions_get":{"tokens":[["text","\/administration\/regions\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_region_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","regionid"],["text","\/administration\/region"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_region_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","regionid"],["text","\/administration\/region"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_region_add":{"tokens":[["text","\/administration\/region\/add"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_region_new":{"tokens":[["text","\/administration\/region\/new"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_region_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","regionid"],["text","\/administration\/region"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"administration_region_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","regionid"],["text","\/administration\/region"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_stores":{"tokens":[["text","\/administration\/stores"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_stores_get":{"tokens":[["text","\/administration\/stores\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_store_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_store_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_store_add":{"tokens":[["text","\/administration\/store\/add"]],"defaults":[],"requirements":[],"hosttokens":[]},"administration_store_new":{"tokens":[["text","\/administration\/store\/new"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"administration_store_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"administration_store_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_store_delete_email":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","emailid"],["text","\/email"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_store_delete_phone":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","phoneid"],["text","\/phone"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"administration_store_delete_address":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","addressid"],["text","\/address"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/store"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"configuration_stores":{"tokens":[["text","\/administration\/configuration\/stores"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"configuration_stores_get":{"tokens":[["text","\/administration\/configuration\/stores\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"configuration_store_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/configuration\/store"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"configuration_store_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/configuration\/store"]],"defaults":[],"requirements":[],"hosttokens":[]},"configuration_store_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","storeid"],["text","\/administration\/configuration\/store"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"configuration_installation":{"tokens":[["text","\/administration\/installation\/sensors"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"installation_sensors_get":{"tokens":[["text","\/administration\/installation\/sensors\/get"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"installation_sensor_get":{"tokens":[["text","\/get"],["variable","\/","[^\/]++","typeid"],["text","\/type"],["variable","\/","[^\/]++","sensorid"],["text","\/administration\/installation\/sensor"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"installation_sensor_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","typeid"],["text","\/type"],["variable","\/","[^\/]++","sensorid"],["text","\/administration\/installation\/sensor"]],"defaults":[],"requirements":[],"hosttokens":[]},"installation_sensor_save":{"tokens":[["text","\/save"],["variable","\/","[^\/]++","typeid"],["text","\/type"],["variable","\/","[^\/]++","sensorid"],["text","\/administration\/installation\/sensor"]],"defaults":[],"requirements":{"_method":"POST|PUT"},"hosttokens":[]},"tcm_login":{"tokens":[["text","\/login"]],"defaults":[],"requirements":[],"hosttokens":[]},"tcm_login_status":{"tokens":[["text","\/login_status"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"login_verify":{"tokens":[["text","\/login_verify"]],"defaults":[],"requirements":[],"hosttokens":[]},"tcm_home":{"tokens":[["text","\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"tcm_profile_change_password":{"tokens":[["text","\/profile\/change\/password"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"usoft_postcode_api":{"tokens":[["text","\/api\/postcode"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]}},"prefix":"","host":"localhost","scheme":"http"});