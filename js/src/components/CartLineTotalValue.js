import React, {Component} from 'react';
import FormatCurrency from './FormatCurrency';

class CartLineTotalValue extends Component {

    shouldComponentUpdate(nextProps, nextState) {
        return !isNaN(nextProps.quantity)
    }

    render() {
        const price = this.props.price;
        const quantity = this.props.quantity;

        return (
            <FormatCurrency value={price * quantity} />
        );
    }
}

export default CartLineTotalValue;