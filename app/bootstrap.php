<?php
require_once "core/BaseModel.php";
require_once 'core/view/View.php';
require_once 'core/view/ViewBag.php';
require_once 'core/Controller.php';
require_once 'core/Html/Html.php';
require_once 'core/Html/HtmlElement.php';
require_once 'core/session/Session.php';
require_once 'core/Interceptor/HandlerInterceptor.php';
require_once 'core/Interceptor/InterceptorManager.php';
require_once 'Exception/RouteException.php';
require_once 'Exception/ActionMethodException.php';
require_once 'Exception/ClassNotDefinedException.php';
require_once 'Exception/ControllerFileException.php';

require_once 'core/modelBinding/IModelBinder.php';
require_once 'core/modelBinding/ModelBinderDictionary.php';
require_once 'core/modelBinding/ModelBinders.php';
require_once 'core/modelBinding/DefaultModelBinder.php';

require_once 'Infrastructure/Abstract/IHashAlgorithm.php';
require_once 'Infrastructure/Concrete/HashSHA1.php';
require_once 'Infrastructure/Repository/Repository.php';
require_once 'Infrastructure/Repository/AutoStoreDb.php';
require_once 'Infrastructure/Repository/Parameter.php';
require_once 'Infrastructure/Repository/StoredProcedure.php';

require_once 'core/route/RouteCollection.php';
require_once 'core/route/Route.php';
require_once 'core/route/Routing.php';
require_once 'App_Start/RouteConfig.php';
require_once 'core/route/RoutePattern.php';

require_once 'core/File.php';

require_once 'core/url/Url.php';

require_once 'core/action/Action.php';

require_once 'Binders/CartModelBinder.php';

require_once 'Entities/Cart.php';
require_once 'Entities/Product.php';
require_once 'Entities/Order.php';
require_once 'Entities/OrderPartial.php';

require_once 'Interceptor/AdminInterceptor.php';

require_once 'App.php';

//try {
    App::start();
    Routing::start(); // запускаем маршрутизатор
//}
//catch (Exception $ex){
//    include "views/Shared/Error.php";
//}