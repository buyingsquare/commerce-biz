<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2020
 */

/* Expected data:
 * - productItems : List of product variants incl. referenced items
 * - basket-add : True to display "add to basket" button, false if not (optional)
 * - require-stock : True if the stock level should be displayed (optional)
 * - itemprop : Schema.org property for the product items (optional)
 * - position : Position is product list to start from (optional)
 */


$enc = $this->encoder();
$position = $this->get( 'position' );


/** client/html/catalog/detail/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );

/** client/html/catalog/detail/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );

/** client/html/catalog/detail/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );

/** client/html/catalog/detail/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/filter
 * @see client/html/url/config
 */
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', [] );

/** client/html/catalog/detail/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * For SEO, it's nice to have product URLs which contains the product names only.
 * Usually, product names are unique so exactly one product is found when resolving
 * the product by its name. If two or more products share the same name, it's not
 * possible to refer to the correct product and in this case, the product ID is
 * required as unique identifier.
 *
 * This setting removes the listed parameters from the URLs of the detail pages.
 *
 * @param array List of parameter names to remove
 * @since 2019.04
 * @category User
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 */
$detailFilter = array_flip( $this->config( 'client/html/catalog/detail/url/filter', ['d_prodid'] ) );


?>
<ul class="list-items">

	<?php foreach( $this->get( 'products', [] ) as $id => $productItem ) : ?>
		<?php $params = array_diff_key( ['d_name' => $productItem->getName( 'url' ), 'd_prodid' => $productItem->getId(), 'd_pos' => $position !== null ? $position++ : ''], $detailFilter ); ?>

		<li class="product <?= $enc->attr( $productItem->getConfigValue( 'css-class' ) ); ?>"
			data-reqstock="<?= (int) $this->get( 'require-stock', true ); ?>"
			itemprop="<?= $this->get( 'itemprop' ); ?>"
			itemtype="http://schema.org/Product"
			itemscope="" >


			<a href="<?= $enc->attr( $this->url( ( $productItem->getTarget() ?: $detailTarget ), $detailController, $detailAction, $params, [], $detailConfig ) ); ?>">

				<div class="media-list">

					<?php if( ( $mediaItem = $productItem->getRefItems( 'media', 'default', 'default' )->first() ) !== null ) : ?>

						<noscript>
							<div class="media-item" itemscope="" itemtype="http://schema.org/ImageObject">
								<img src="<?= $enc->attr( $this->content( $mediaItem->getPreview() ) ); ?>" alt="<?= $enc->attr( $mediaItem->getName() ); ?>" />
								<meta itemprop="contentUrl" content="<?= $enc->attr( $this->content( $mediaItem->getPreview() ) ); ?>" />
							</div>
						</noscript>

						<?php foreach( $productItem->getRefItems( 'media', 'default', 'default' ) as $mediaItem ) : ?>

							<div class="media-item">
								<img class="lazy-image"
									src="data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEEAAEALAAAAAABAAEAAAICTAEAOw=="
									data-src="<?= $enc->attr( $this->content( $mediaItem->getPreview() ) ); ?>"
									data-srcset="<?= $enc->attr( $this->imageset( $mediaItem->getPreviews() ) ) ?>"
									alt="<?= $enc->attr( $mediaItem->getName() ); ?>"
								/>
							</div>

						<?php endforeach; ?>
					<?php endif; ?>

				</div>

				<div class="text-list">
					<h2 itemprop="name"><?= $enc->html( $productItem->getName(), $enc::TRUST ); ?></h2>

					<?php foreach( $productItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>

						<div class="text-item" itemprop="description">
							<?= $enc->html( $textItem->getContent(), $enc::TRUST ); ?>
						</div>

					<?php endforeach; ?>

				</div>

			</a>


			<div class="offer" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

				<div class="stock-list">
					<div class="articleitem stock-actual"
						data-prodid="<?= $enc->attr( $productItem->getId() ); ?>"
						data-prodcode="<?= $enc->attr( $productItem->getCode() ); ?>">
					</div>

					<?php foreach( $productItem->getRefItems( 'product', null, 'default' ) as $articleId => $articleItem ) : ?>

						<div class="articleitem"
							data-prodid="<?= $enc->attr( $articleId ); ?>"
							data-prodcode="<?= $enc->attr( $articleItem->getCode() ); ?>">
						</div>

					<?php endforeach; ?>

				</div>

				<div class="price-list">
					<div class="articleitem price price-actual"
						data-prodid="<?= $enc->attr( $productItem->getId() ); ?>"
						data-prodcode="<?= $enc->attr( $productItem->getCode() ); ?>">

						<?= $this->partial(
							/** client/html/common/partials/price
							 * Relative path to the price partial template file
							 *
							 * Partials are templates which are reused in other templates and generate
							 * reoccuring blocks filled with data from the assigned values. The price
							 * partial creates an HTML block for a list of price items.
							 *
							 * The partial template files are usually stored in the templates/partials/ folder
							 * of the core or the extensions. The configured path to the partial file must
							 * be relative to the templates/ folder, e.g. "partials/price-standard.php".
							 *
							 * @param string Relative path to the template file
							 * @since 2015.04
							 * @category Developer
							 */
							$this->config( 'client/html/common/partials/price', 'common/partials/price-standard' ),
							array( 'prices' => $productItem->getRefItems( 'price', null, 'default' )->first() ?: map() )
						); ?>

					</div>

					<?php if( $productItem->getType() === 'select' ) : ?>
						<?php foreach( $productItem->getRefItems( 'product', 'default', 'default' ) as $prodid => $product ) : ?>
							<?php if( !( $prices = $product->getRefItems( 'price', null, 'default' ) )->isEmpty() ) : ?>

								<div class="articleitem price"
									data-prodid="<?= $enc->attr( $prodid ); ?>"
									data-prodcode="<?= $enc->attr( $product->getCode() ); ?>">
									<?= $this->partial(
										$this->config( 'client/html/common/partials/price', 'common/partials/price-standard' ),
										array( 'prices' => $prices )
									); ?>
								</div>

							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

			</div>


			<?php if( $this->get( 'basket-add', false ) ) : ?>
				<?php
					$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
					$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
					$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
					$basketConfig = $this->config( 'client/html/basket/standard/url/config', [] );
				?>

				<form method="POST" action="<?= $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, [], [], $basketConfig ) ); ?>">
					<!-- catalog.lists.items.csrf -->
					<?= $this->csrf()->formfield(); ?>
					<!-- catalog.lists.items.csrf -->

					<?php if( $basketSite = $this->config( 'client/html/basket/standard/url/site' ) ) : ?>
						<input type="hidden" name="<?= $this->formparam( 'site' ) ?>" value="<?= $enc->attr( $basketSite ) ?>" />
					<?php endif ?>

					<?php if( $productItem->getType() === 'select' ) : ?>

						<div class="items-selection">
							<?= $this->partial(
								$this->config( 'client/html/common/partials/selection', 'common/partials/selection-standard' ),
								['productItems' => $productItem->getRefItems( 'product', 'default', 'default' )]
							); ?>
						</div>

					<?php endif; ?>

					<div class="items-attribute">

						<?= $this->partial(
							$this->config( 'client/html/common/partials/attribute', 'common/partials/attribute-standard' ),
							['productItem' => $productItem]
						); ?>

					</div>

					<div class="addbasket">
						<div class="input-group">
							<input type="hidden" value="add"
								name="<?= $enc->attr( $this->formparam( 'b_action' ) ); ?>"
							/>
							<input type="hidden" value="<?= $id; ?>"
								name="<?= $enc->attr( $this->formparam( array( 'b_prod', 0, 'prodid' ) ) ); ?>"
							/>
							<input type="number" class="form-control" value="1"
								 min="1" max="2147483647" maxlength="10" step="1" required="required" <?= !$productItem->isAvailable() ? 'disabled' : '' ?>
								name="<?= $enc->attr( $this->formparam( array( 'b_prod', 0, 'quantity' ) ) ); ?>"
							/><!--
							--><button class="btn btn-primary" type="submit" value="" <?= !$productItem->isAvailable() ? 'disabled' : '' ?> >
								<?= $enc->html( $this->translate( 'client', 'Add to basket' ), $enc::TRUST ); ?>
							</button>
						</div>
					</div>

				</form>

			<?php endif; ?>

		</li>

	<?php endforeach; ?>

</ul>