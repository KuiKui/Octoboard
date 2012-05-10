<?php
/*
 *  $Id: PropelException.php 1262 2009-10-26 20:54:39Z francois $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://propel.phpdb.org>.
 */

/**
 * The base class of all exceptions thrown by Propel.
 * @author     Hans Lellelid <hans@xmpl.org>
 * @version    $Revision: 1262 $
 * @package    propel
 */
class PropelException extends Exception {

	/** The nested "cause" exception. */
	protected $cause;

	function __construct($p1, $p2 = null) {

		$cause = null;

		if ($p2 !== null) {
			$msg = $p1;
			$cause = $p2;
		} else {
			if ($p1 instanceof Exception) {
				$msg = "";
				$cause = $p1;
			} else {
				$msg = $p1;
			}
		}

		parent::__construct($msg);

		if ($cause !== null) {
			$this->cause = $cause;
			$this->message .= " [wrapped: " . $cause->getMessage() ."]";
		}
	}

	function getCause() {
		return $this->cause;
	}

}
