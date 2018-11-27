<?php

class View {
	public function admin_generate($content_view, $template_view, $data = null) {
		require DCF_PATH_ADMIN_VIEWS . $template_view;
	}
	public function public_generate($content_view, $template_view, $data = null) {
		require DCF_PATH_PUBLIC_VIEWS . $template_view;
	}
}