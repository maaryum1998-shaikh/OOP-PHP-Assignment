<?php 
// Trait DigitalCopy with the method getDigitalCopy
trait DigiCopy{
  public function getDigitalCopy()
    {
        return "This is a digital copy of '{$this->getTitle()}'.<br>";
    }
}

// Abstract class LibraryItem with abstract methods
abstract class LibraryItem
{
    protected $Title;
    protected $Type;

// Static property to track total number of items
private static $totalItems = 0;

    // Constructor to initialize title and type
    public function __construct($Title, $Type)
    {
        $this->setTitle($Title);
        $this->setType($Type);
        self::incrementTotalItems();
    }

    // Abstract methods to be implemented by subclasses
    abstract public function getTitle();
    abstract public function getType();

// Getter and Setter for Title
public function getTitleProperty(){
  return $this->Title;
}
public function setTitle($Title){
if (empty($Title)){
  throw new Exception("Title cannot be empty");
}
$this->Title=$Title;
  
} 

//Getter and Setter for Type
public function getTypeProperty(){
  return $this->Type;
}
public function setType($Type) {
  $allowedTypes = ['Book', 'Magazine', 'DVD'];
  if (!in_array($Type, $allowedTypes)) {
    throw new Exception("Invalid type. Allowed types are: " . implode(", ", $allowedTypes));  }
      $this->Type = $Type;
  }


// Static method to increment the total number of items
public static function incrementTotalItems() {
  self::$totalItems++;
}

// Static method to get the total number of items
public static function getTotalItems() {
  return self::$totalItems;
}
}
// Interface Borrowable with methods borrowItem and returnItem
interface Borrowable
{
    public function borrowItem();
    public function returnItem();
}

// Class Book implementing Borrowable interface and extending LibraryItem
class Book extends LibraryItem implements Borrowable
{
    // Constructor to initialize title and set type as 'Book'
    private $Borrowed = false;

  
      public function __construct($Title){
      parent::__construct($Title, "Book");}
      
        // Destructor to demonstrate its use
    public function __destruct()
    {
        echo "Book'{$this->Title}' is being destroyed .<br>";
    }

    // Implementing abstract methods from LibraryItem
    public function getTitle()
    {
        return $this->Title;
    }

    public function getType()
    {
        return $this->Type;
    }

    // Implementing methods from Borrowable interface
    public function BorrowItem()
    {
      if (!$this->Borrowed){
        $this->Borrowed =true;
        return "Book '{$this->Title}' has been borrowed" . "\n" . "<br>";
      }
      return "Book '{$this->Title}' is already borrowed" . "\n" . "<br>";
    }
    public function returnItem()
    {
    
      if ($this->Borrowed){
        $this->Borrowed =false;
        return "Book '{$this->Title}' has been returned\n" . "<br>";
      }
      return "\nBook '{$this->Title}' wasn't borrowed\n". "<br>";
    }
    
  }

// Class EBook that extends Book and uses the DigitalCopy trait 
class EBook extends Book{
  use DigiCopy; // Use the trait
  
  // Constructor to initialize title and set type as 'EBook'
  public function __construct($Title)
{
  parent::__construct($Title);
}}


// Class Magazine implementing Borrowable interface and extending LibraryItem
class Magazine extends LibraryItem implements Borrowable
{
  private $Borrowed = false;

  // Constructor to initialize title and set type as 'Magazine' 
    public function __construct($Title)
    {
        parent::__construct($Title, "Magazine");
    }

    // Implementing abstract methods from LibraryItem
    public function getType()
    {
        return $this->Type;
    }

    public function getTitle()
    {
        return $this->Title;
    }

    // Implementing borrowItem and returnItem from Borrowable interface
    public function BorrowItem()
    {
      if (!$this->Borrowed){
        $this->Borrowed =true;
        return "\nMagazine '{$this->Title}' has been borrowed" . "<br>";
      }
      return "\nBook '{$this->Title}' is already borrowed" . "<br>";
    }
    public function returnItem()
    {
    
      if (!$this->Borrowed){
        $this->Borrowed =false;
        return "\nMagazine '{$this->Title}' has been returned\n". "<br>";
      }
      return "\nMagazine '{$this->Title}' wasn't borrowed\n" . "<br>";
    
    }
}

// Class DVD implementing Borrowable interface and extending LibraryItem
class DVD extends LibraryItem implements Borrowable
{private $borrowed = false;
    // Constructor to initialize title and set type as 'DVD'
    public function __construct($Title)
    {
        parent::__construct($Title, "DVD");
    }

    // Implementing abstract methods from LibraryItem
    public function getTitle()
    {
        return $this->Title;
    }

    public function getType()
    {
        return $this->Type;
    }

// Implementing Borrowable methods
public function borrowItem() {
  if (!$this->borrowed) {
      $this->borrowed = true;
      return "\nDVD '{$this->Title}' has been borrowed.\n" . "<br>";
  }
  return "\nDVD '{$this->Title}' is already borrowed.\n". "<br>";
}

public function returnItem() {
  if ($this->borrowed) {
      $this->borrowed = false;
      return "\nDVD '{$this->Title}' has been returned.\n" . "<br>";
  }
  return "\nDVD '{$this->Title}' wasn't borrowed.\n". "<br>";
}
}

// Library class to manage LibraryItem objects
class Library
{
    private $items = [];

    // Method to add a LibraryItem object to the library 
    public function addItem(LibraryItem $item)
    {
        $this->items[] = $item;
    }

    // Methods to list all LibraryItem objects in the Library
    public function listItem()
    {
        foreach ($this->items as $item) {
            echo "<br><br>" . "Title: " . $item->getTitle() . ", Type: " . $item->getType() . "<br>";
        }
    }
// Method to borrow an item by title
public function borrowItem($title) {
  foreach ($this->items as $item) {
      if ($item->getTitle() === $title && $item instanceof Borrowable) {
          return $item->borrowItem();
      }
  }
  return "Item '{$title}' not found or not borrowable.";
}

// Method to return an item by title
public function returnItem($title) {
  foreach ($this->items as $item) {
      if ($item->getTitle() === $title && $item instanceof Borrowable) {
          return $item->returnItem();
      }
  }
  return "Item '{$title}' not found or not returnable.";
}
}

// Creating a Library instance
$library = new Library();

// Creating Book, Magazine, and DVD objects
$Book = new Book("To Kill a Mockingbird");
echo $Book->borrowItem();
echo $Book->returnItem(); 

$magazine = new Magazine("National Geographic");
echo $magazine->borrowItem();
echo $magazine->returnItem(); 

$dvd = new DVD("The Matrix");
echo $dvd->borrowItem(); 
echo $dvd->returnItem(); 

// Adding items to the Library
$library->addItem($Book);
$library->addItem($magazine);
$library->addItem($dvd);

// Listing all items in the library
echo $library->listItem() . "\n";

// Demonstrate destructor (when object is unset)
unset($Book); // Explicitly call destructor for demonstration

// Show total items after unset (since static property is still intact)
echo "Total items in the library: " . LibraryItem::getTotalItems() . "\n";

// Creating an EBook instance
$eBook = new EBook("Digital Marketing 101");
echo $eBook->borrowItem();
echo $eBook->getDigitalCopy();
echo $eBook->returnItem();
?>
