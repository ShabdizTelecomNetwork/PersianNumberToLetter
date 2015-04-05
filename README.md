# PersianNumberToLetter
Convert a number to persian words

## Usage 

    $numberToLetter = new Number2Letter();
    $numberToLetter = new NumberToLetter();
    $numberToLetter->join = ' و ';
    $letter = $numberToLetter->solve('83348939399399393'); // Note : Up to 66 digits.
    echo $letter;
    // Output : 
    // هشتاد و سه تریلیون و سیصد و چهل و هشت بیلیون و نهصد و سی و نه میلیارد و سیصد و نود و نه میلیون و سیصد و نود و نه هزار و سیصد و نود و سه  
