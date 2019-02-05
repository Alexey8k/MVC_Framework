import React from 'react';

function ButtonPanel(props) {
    const checkoutUrl = "/Cart/Checkout?returnUrl=" + encodeURIComponent(window.location.pathname);
    const btnCheckout = props.isShowCart && <a href={checkoutUrl}>Оформить заказ</a>;

    return (
        <p align="center" className="actionButtons" ng-controller="miniCartController">
            <input type="button" value="Продолжить покупки" ng-click="closeMiniCart()" style={{display: props.isShowCart === null && 'none'}} />
            {btnCheckout}
        </p>
    );
}

export default ButtonPanel;