<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AuthorizedIp extends Model{


    use HasFactory;

    public $timestamps = false;

    protected $table = 'authorized_ip';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'ip_address',
    ];


    /**
     * Get the ID of the IP address.
     *
     * @return int|null
     */
    public function getId(): ?int{
        return $this->id;
    }


    /**
     * Get the IP address.
     *
     * @return string|null
     */
    public function getIpAddress(): ?string{
        return $this->ip_address;
    }


    /**
     * Set the IP address.
     *
     * @param string $ipAddress
     * @return self
     */
    public function setIpAddress(string $ipAddress): self{
        $this->ip_address = $ipAddress;
        return $this;
    }

    /**
     * Get the IP address as a string.
     *
     * @return string
     */
    public function __toString(): string{
        return $this->ip_address;
    }
}

?>