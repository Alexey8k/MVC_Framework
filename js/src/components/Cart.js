import React, {Component} from 'react';
// import $ from 'jquery';
import CartLines from './CartLines';
import FormatCurrency from './FormatCurrency';

class Cart extends Component {

    state = { cart: this.props.cart};

    render() {

        return (
                <table>
                    <thead>
                    <tr>
                        <th style={{textAlign: "center"}}>Количество</th>
                        <th style={{textAlign: "left"}}>Наименование</th>
                        <th style={{textAlign: "right"}}>Цена</th>
                        <th style={{textAlign: "right"}}>Итоговая цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    <CartLines data={this.state.cart !== null ? this.state.cart.lineCollection : []}
                               setCart={this.setCart.bind(this)} />
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colSpan="3" align="right">Итого:</td>
                        <td align="right">
                            <FormatCurrency value={this.state.cart !== null ? this.state.cart.totalValue : 0} />
                        </td>
                    </tr>
                    </tfoot>
                </table>
        );
    }

    setCart(cart) {
        this.setState({cart: cart});
    }
}

export default Cart;
