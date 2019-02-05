import React, {Component} from 'react';
import CartLine from './cartLine';

class CartLines extends Component {

    render() {

        return this.props.data.map((cartLine) =>
            <CartLine key={cartLine.product.id}
                      product={cartLine.product}
                      quantity={cartLine.quantity}
                      setCart={this.props.setCart}/>
        );
    }
}

export default CartLines;