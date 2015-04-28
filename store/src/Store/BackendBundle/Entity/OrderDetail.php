<?php
//
//namespace Store\BackendBundle\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * Class OrderDetail
// * @package Store\BackendBundle\Entity
// */
//class OrderDetail {
//
//    /**
//     * @var \Orders
//     *
//     * @ORM\ManyToOne(targetEntity="Orders")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
//     * })
//     * @ORM\Id
//     */
//    private $orders;
//
//    /**
//     * @var \Product
//     *
//     * @ORM\ManyToOne(targetEntity="Product")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
//     * })
//     *
//     * @ORM\Id
//     */
//    private $product;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="quantity", type="integer", nullable=true)
//     */
//    private $quantity;
//
//    /**
//     * @var float
//     *
//     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
//     */
//    private $price;
//
//
//
//
//
//    /**
//     * @param \Orders $order
//     */
//    public function setOrders($orders)
//    {
//        $this->orders = $orders;
//    }
//
//    /**
//     * @return \Orders
//     */
//    public function getOrders()
//    {
//        return $this->orders;
//    }
//
//    /**
//     * @param float $price
//     */
//    public function setPrice($price)
//    {
//        $this->price = $price;
//    }
//
//    /**
//     * @return float
//     */
//    public function getPrice()
//    {
//        return $this->price;
//    }
//
//    /**
//     * @param \Product $product
//     */
//    public function setProduct($product)
//    {
//        $this->product = $product;
//    }
//
//    /**
//     * @return \Product
//     */
//    public function getProduct()
//    {
//        return $this->product;
//    }
//
//    /**
//     * @param int $quantity
//     */
//    public function setQuantity($quantity)
//    {
//        $this->quantity = $quantity;
//    }
//
//    /**
//     * @return int
//     */
//    public function getQuantity()
//    {
//        return $this->quantity;
//    }
//
//
//}