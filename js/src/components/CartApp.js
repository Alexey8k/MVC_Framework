import React, {Component} from 'react';
import Cart from './Cart';
import ButtonPanel from './ButtonPanel';


class CartApp extends Component {

    state = {
        cart: null
    };

    render() {
        const isShowCart = this.state.cart && this.state.cart.totalCount !== 0;
        const cart = isShowCart !== null && (isShowCart
            ? <Cart cart={this.state.cart} />
            : <div>Ваша корзина пустая</div>);

        return(
            <div>
                {cart}
                <ButtonPanel isShowCart={isShowCart} />
            </div>
        );
    }

    componentDidMount() {
        this.initCart();
    }

    initCart() {
        $.ajax({
                url: "/Cart/GetCart",
                method: 'GET',
                cache: false,
                success: function (data) {
                    this.setState({cart: JSON.parse(data)});
                }.bind(this),
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    }
}

export default CartApp;