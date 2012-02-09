window.CMS = window.CMS || {};

var forms = $('#main form');

var catcher = function()
{
	var changed = false;

	forms.each
	(
		function()
		{
			if($(this).data('initialForm') != $(this).serialize())
			{
				changed = true;
				$(this).addClass('changed');
			}
			else
			{
				$(this).removeClass('changed');
			}
		}
	);

	if (changed)
	{
		return 'A form on this page has been changed.  Are you sure you want to leave this page?';
	}
};

$
(
	function()
	{
		$('form').each
		(
			function()
			{
				$(this).data('initialForm', $(this).serialize());
			}
		)
		.submit
		(
			function(e)
			{
				var formEl = this;
				var changed = false;

				$('form').each
				(
					function()
					{
						if (this != formEl && $(this).data('initialForm') != $(this).serialize())
						{
							changed = true;
							$(this).addClass('changed');
						}
						else
						{
							$(this).removeClass('changed');
						}
					}
				);
		
				if (changed && !confirm('Another form on this page has been changed. Are you sure you want to continue with this form submission?'))
				{
					e.preventDefault();
				}
				else
				{
					$(window).unbind('beforeunload', catcher);
				}

			}
		);

		$(window).bind('beforeunload', catcher);
	}
);