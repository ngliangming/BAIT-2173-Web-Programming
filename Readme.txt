=========================================================================================================
Side Navigation Bar Function
	-The search function present in the products page and manage products page 
	can search for products based on name, description or category.
		
	-The search function present in the manage order page can 
	search for products based on order id, status, product name, user name, 
	user email, product category, product description, or availability.
		
	-The search function present in the manage category page can 
	search for products based on category id or name.
	
	-All side navigation bar present in the webpage automatically generates buttons for 
	categories present in the database, including newly added ones
	(except for the manage category page, as that is redundant).
	
	-If there is too many categories to fit into the side navigation bar, the side navigation
	bar will become scrollable using the mouse wheel.
	
	-All search function present in the webpage can be narrowed down by category using the 
	side navigation buttons (i.e. the default 'All Products' will show all categories. 
	If selected 'Chair' category, the search will only show products from the chair category).
	
	
	
=========================================================================================================
Login Functions
	-Logging in/out does not redirect the user to a different page, but instead remains in the same page.
	(i.e. logging in in products page will stay in products page).

	-Login errors/failures are shown as a red alert bar below the top navigation bar.
	
	-The user can change their passwords when logged in.
	
	-The user can delete their account in the change password page. If the user does delete 
	their account, all existing products that were in pending status will be cancelled.

	-If the user is not logged in in the shopping cart page, the "Continue To Checkout" button 
	will prompt the user to login instead. If the user is already logged in, it will proceed as normal.
	
	
	
=========================================================================================================
Admin Management Pages
	-In all admin management pages, a success message will popup after changing anything 
	(i.e. edit product/ fulfill orders).
	
	-In all admin management pages, the table heading can be clicked to sort the table.

	
	
=========================================================================================================
Products Page
	-In products page, unavailable products will have their quantity and add to cart button disabled, 
	with a 'Unavailable' message on the lower left.

	
	
=========================================================================================================
Contact Us Page
	-Contains an embedded interactive Google Maps with a set location.