<?php

require 'Image.php';

class Upload {
	public function work($data, $settings) {
		$return = [];
		$errors = '';

		for($i=1; $i<count($data)+1; $i++){

			if($settings['size']) {
				if($data['file_'.$i]["size"] > $settings['size'] * 1048576){
					$errors .= "Загружаемый файл должен быть не больше {$settings['size']} Мбайт \n";
				}
			}

			$checkTypes = [];
			if(count($settings['types'])) {

				for ( $k=0; $k<count($settings['types']); $k++ ) {
					if ( $data['file_'.$i]['type'] == "image/{$settings['types'][$k]}" ) {
						$checkTypes[] = 1;
						break;
					}
				}
				if (!count($checkTypes)) {
					$errors .= "Можно загружать только файлы: " . join(', ', $settings['types']) . "\n";
				}
			}

			if ($data['file_'.$i]["error"]) {
				$errors .= 'Системная ошибка загрузки файла';
			}

			if($errors[1]) { return [false, $errors]; }

			$nameFile = $this->create_file($data['file_'.$i], $settings);

			$return[] = [
				'name' => 'file_'.$i,
				'value' => $nameFile
			];
		}

		return $return;
	}

	public function create_file($file, $settings) {
		$filePath = $file['tmp_name'];
		$fileExtension = strtolower(end(explode('.', $file['name'])));

		$realSize = getimagesize($filePath);
		$width = $settings['width'] ? $settings['width'] : $realSize[0];
		$height = $settings['height'] ? $settings['height'] : $realSize[1];


		$image = new Image;

		$nameFile = time() . uniqid() . '.' . $fileExtension;
		$newPath = $settings['dir'] . $nameFile;

		$image->load($filePath);
		$image->resize($width, $height);
		$image->save($newPath);

		return $nameFile;
	}
}