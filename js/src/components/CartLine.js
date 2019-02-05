import React, {Component} from 'react';
import CartLineTotalValue from './CartLineTotalValue';
import FormatCurrency from './FormatCurrency';


class CartLine extends Component {

    state = {
        quantity: this.props.quantity
    };

    componentDidUpdate(prevProps, prevState) {
        if (!isNaN(this.state.quantity))
            this.submitChangeQuantity();
    }

    shouldComponentUpdate(nextProps, nextState) {
        return this.state.quantity !== nextState.quantity;
    }

    render() {
        const {product} = this.props;
        const {quantity} = this.state;

        return (
            <tr>
                <td style={{textAlign: "center"}}>
                    <div className="cart-amount">
                        <button type="button" name="minus" className="cart-amount-qnt-btn" onClick={this.onChangeQuantity.bind(this)} ><span>-</span></button>
                        <input type="text" name="quantity" className="cart-amount-input-text" value={quantity || ""} onChange={this.onChangeQuantity.bind(this)} />
                        <button type="button" name="plus" className="cart-amount-qnt-btn" onClick={this.onChangeQuantity.bind(this)} ><span>+</span></button>
                    </div>
                </td>
                <td style={{textAlign: "left"}}>{product.name}</td>
                <td style={{textAlign: "right"}}>
                    <FormatCurrency value={product.price} />
                </td>
                <td style={{textAlign: "right"}}>
                    <CartLineTotalValue price={product.price} quantity={quantity} />
                </td>
                <td style={{width: "1%"}}>
                    <input className="actionButtons" type="button" name="delete" onClick={this.onChangeQuantity.bind(this)} value="Удалить" />
                </td>
            </tr>
        );
    }

    onChangeQuantity(event) {
        const updateQuantity = (newQuantity) => this.setState({
            quantity: newQuantity
        });

        switch (event.currentTarget.name) {
            case "plus":
                updateQuantity(this.state.quantity + 1);
                break;
            case "minus":
                updateQuantity(this.state.quantity - 1);
                break;
            case "quantity":
                const quantity = parseInt(event.currentTarget.value);
                updateQuantity(quantity);
                break;
            case "delete":
                updateQuantity(0);
                break;
        }
    }

    submitChangeQuantity() {
        $.ajax({
                url: "/Cart/ChangeQuantity1",
                method: 'GET',
                cache: false,
                data: {
                    id: this.props.product.id,
                    quantity: this.state.quantity
                },
                success: function (data) {
                    const cart = JSON.parse(data);
                    if (cart.totalCount === 0) {
                        this.closeMiniCart();
                        return;
                    }
                    this.props.setCart(JSON.parse(data));
                    this.updateSummary();
                }.bind(this),
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    }

    updateSummary() {
        $.ajax({
            url: '/Cart/Summary',
            method: 'GET',
            success: function (data) {
                $('#cart-summary').html(data);
            }
        });
    }

    closeMiniCart() {
        $("#mini-cart").dialog("close");
    }

}

export default CartLine;